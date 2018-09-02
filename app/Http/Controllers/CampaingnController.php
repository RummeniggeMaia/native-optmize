<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Campaingn;
use App\Creative;
use App\User;
use App\Segmentation;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Providers\IP2Location;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CampaingnController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    const DISK = "public";
    public $types = ['CPA'=>'CPA','CPC'=>'CPC','CPM'=>'CPM'];

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $campaingns = [];
        return view('campaingns.index', compact('campaingns'));
    }

    public function indexDataTable() {
        $campaingns = DB::table('campaingns')->where('user_id', Auth::id())->get();
        return Datatables::of($campaingns)->addColumn('edit', function($campaingn) {
                    return view('comum.button_edit', [
                        'id' => $campaingn->id,
                        'route' => 'campaingns.edit'
                    ]);
                })->addColumn('show', function($campaingn) {
                    return view('comum.button_show', [
                        'id' => $campaingn->id,
                        'route' => 'campaingns.show'
                    ]);
                })->addColumn('delete', function($campaingn) {
                    return view('comum.button_delete', [
                        'id' => $campaingn->id,
                        'route' => 'campaingns.destroy'
                    ]);
                })->editColumn('type_layout', function($campaingn) {
                    $types = array(
                        '0' =>  '-',
                        '1' => 'Native',
                        '2' => 'Smart Link',
                        '3' => 'Banner Square (300x250)',
                        '4' => 'Banner Mobile (300x100)',
                        '5' => 'Banner Footer (928x244)',
                        '6' => 'Vídeo',
                    );
                    return $campaingn->type_layout ? $types[$campaingn->type_layout] : '-';
                })->editColumn('paused', function($campaingn) {
                    if ($campaingn->paused) {
                        return view('comum.paused_on');
                    } else {
                        return view('comum.paused_off');
                    }
                })->editColumn('cpc', function($campaingn) {
                    return 'R$ ' . $campaingn->cpc;
                })->editColumn('cpm', function($campaingn) {
                    return 'R$ ' . $campaingn->cpm;
                })->editColumn('ceiling', function($campaingn) {
                    return 'R$ ' . $campaingn->ceiling;
                })->editColumn('status', function($campaingn) {
                    if ($campaingn->status) {
                        return view('comum.status_on');
                    } else {
                        return view('comum.status_waiting')->with(['name' => 'validação']);;
                    }
                })->rawColumns(
                        ['edit', 'show', 'delete', 'status', 'paused']
                )->make(true);
    }

    public function indexInatives() {
        return view('campaingns.index_inatives');
    }

    public function inativesDataTable() {
        $campaingns = Campaingn::with('user')
                        ->where([
                            // ['user_id', '!=', Auth::id()],
                            ['status', false]])->get();
        return Datatables::of($campaingns)->addColumn('show', function($campaingn) {
                return view('comum.button_show', [
                    'id' => $campaingn->id,
                    'route' => 'campaingns.show'
                ]);
            })->addColumn('activate', function($campaingn) {
                return view('comum.button_activate', [
                    'id' => $campaingn->id,
                    'route' => 'campaingns.activate'
                ]);
            })->editColumn('type_layout', function($campaingn) {
                return array(
                        '0' =>  '-',
                        '1' => 'Native',
                        '2' => 'Smart Link',
                        '3' => 'Banner Square (300x250)',
                        '4' => 'Banner Mobile (300x100)',
                        '5' => 'Banner Footer (928x244)',
                        '6' => 'Vídeo',
                    )[$campaingn->type_layout];
            })->editColumn('status', function($campaingn) {
                if ($campaingn->status) {
                    return view('comum.status_on');
                } else {
                    return view('comum.status_off');
                }
            })->editColumn('users.name', function($campaingn) {
                $user = User::find($campaingn->user_id);
                return $user ? $user->name : '-';
            })->rawColumns(
                ['show', 'status', 'activate']
            )->make(true);
    }

    public function campaignsDashboardTable() {
        $campaigns = Campaingn::with('campaignLogs')
                        ->where(['user_id' => Auth::id()])->get();
        return Datatables::of($campaigns)->editColumn('clicks', function($campaign) {
                return $campaign->campaignLogs->sum('clicks');
            })->editColumn('impressions', function($campaign) {
                return $campaign->campaignLogs->sum('impressions');
            })->editColumn('revenues', function($campaign) {
                $revenues = $campaign->campaignLogs->sum('revenues');
                return 'R$ ' . number_format($revenues, 4);
            })->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (!Auth::user()->hasRole('admin')) {
            unset($this->types['CPA']);
        }
        $creatives = Creative::where([
                'user_id'=>Auth::id(),
                'type_layout'=>1
                ])->orderBy('name', 'asc')->get();
        return view('campaingns.create')->with([
            'creatives' => $creatives,
            'types'=>$this->types,
            'countries' => $this->countries()
        ]);
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $validacao = $this->validar($post);
        if ($validacao->fails()) {
            $creatives = Creative::where(
                [
                    'user_id' => Auth::id(),
                    'type_layout' => $post['type_layout']
                ]
            )->orderBy('name', 'asc')->get();
            if (!Auth::user()->hasRole('admin')) {
                unset($this->types['CPA']);
            }
            if ($post['type_layout'] == 2) {
                unset($this->types['CPM']);
            }
            return view('campaingns.create')
                ->with([
                    'creatives' => $creatives, 
                    'types'=>$this->types,
                    'countries' => $this->countries()
                ])->withErrors($validacao);
        } else {
            DB::beginTransaction();
            try {
                $post['user_id'] = Auth::id();
                $post['hashid'] = Hash::make(Auth::id() . "hash" . Carbon::now()->toDateTimeString());
                $post['expires_in'] = date('Y-m-d', strtotime("+30 days"));
                if ($post['type'] == "CPC") {
                    $post['cpm'] = 0.0;
                } else if ($post['type'] == "CPM") {
                    $post['cpc'] = 0.0;
                } else {
                    $post['cpc'] = 0.0;
                    $post['cpm'] = 0.0;
                }
                if (Auth::user()->hasRole('admin')) {
                    $campaingn['status'] = true;
                }
                $campaingn = Campaingn::create($post);
                $campaingn->creatives()->sync($post['creatives']);
                Segmentation::create([
                    'country' => $post['country'],
                    'device' => $post['device'],
                    'campaingn_id' => $campaingn->id
                ]);
                DB::commit();
                return redirect('campaingns')
                                ->with('success', 'Campanha cadastrada com sucesso.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect('campaingns')
                                ->with('error', 'Erro ao cadastrar Campanha.');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $campaingn = Campaingn::with(['user'])->where('id', $id)->first();
        if ($campaingn == null) {
            return back()->with('error'
                            , 'Campanha não registrada no sistema.');
        } else if ($campaingn->user->id != Auth::id() && !Auth::user()->hasRole('admin')) {
            return back()->with('error'
                            , 'Não pode exibir os dados desta Campanha.');
        } else {
            return view('campaingns.show', compact('campaingn'));
        }
    }

    public function creativesDataTable($id) {
        $campaingn = Campaingn::with(['user', 'creatives'])->find($id);
        if ($campaingn == null) {
            return null;
        } else if ($campaingn->user->id != Auth::id() && !Auth::user()->hasRole('admin')) {
            return [];
        } else {
            return Datatables::of($campaingn->creatives)
                ->editColumn('image', function($creative) {
                    return view('comum.image', [
                        'image' => $creative->image
                    ]);
                })->rawColumns(['image'])->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $campaingn = Campaingn::with(['user', 'segmentation'])->where('id', $id)->first();
        if ($campaingn == null) {
            return back()->with('error'
                            , 'Campaingn não registrada no sistema.');
        } else if ($campaingn->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode editar os dados desta Campaingn.');
        } else {
            $creatives = Creative::where([
                ['user_id', Auth::id()],
                ['type_layout', $campaingn->type_layout]
            ])->get();
            if ($campaingn->type_layout == 2) {
                unset($this->types['CPM']);
            }
            if (!Auth::user()->hasRole('admin')) {
                unset($this->types['CPA']);
            }
            return view('campaingns.update', compact('campaingn'))
                            ->with([
                                'creatives' => $creatives,
                                'types' => $this->types,
                                'countries' => $this->countries()
                            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $post = $request->all();
        $validacao = $this->validar($post);
        $campaingn = Campaingn::with(['user', 'segmentation'])->where('id', $id)->first();
        if ($validacao->fails()) {
            $creatives = Creative::where(
                [
                    'user_id' => Auth::id(),
                    'type_layout' => $post['type_layout']
                ]
            )->orderBy('name', 'asc')->get();
            if ($post['type_layout'] == 2) {
                unset($this->types['CPM']);
            }
            if (!Auth::user()->hasRole('admin')) {
                unset($this->types['CPA']);
            }
            return view('campaingns.update', compact('campaingn'))
                ->with([
                    'creatives' => $creatives, 
                    'types'=>$this->types,
                    'countries' => $this->countries()
                ])->withErrors($validacao);
        } else {
            if ($campaingn == null) {
                return back()->with('error'
                                , 'Campaingn não registrada no sistema.');
            } else if ($campaingn->user->id != Auth::id()) {
                return back()->with('error'
                                , 'Não pode editar os dados desta Campaingn.');
            } else {
                DB::beginTransaction();
                try {
                    if ($post['type'] == "CPC") {
                        $post['cpm'] = 0.0;
                    } else if ($post['type'] == "CPM") {
                        $post['cpc'] = 0.0;
                    } else {
                        $post['cpc'] = 0.0;
                        $post['cpm'] = 0.0;
                    }
                    $campaingn->update($post);
                    $campaingn->creatives()->sync($post['creatives']);
                    if (!$campaingn->segmentation) {
                        Segmentation::create([
                            'country' => $post['country'],
                            'device' => $post['device'],
                            'campaingn_id' => $campaingn->id
                        ]);
                    } else {
                        $campaingn->segmentation->country = $post['country'];
                        $campaingn->segmentation->device = $post['device'];
                        $campaingn->segmentation->save();
                    }
                    DB::commit();
                    return redirect('campaingns')
                                ->with('success', 'Campanha atualizada com sucesso.');
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->back()
                                ->with('error', 'Campanha não pode ser atualizada.');
                }
            }
        }
    }

    public function activate(Request $request, $id) {
        $campaign = Campaingn::find($id);
        if ($campaign) {
            $campaign['status'] = true;
            $campaign->save();
            return redirect()->back()
                ->with('success', 'Campanha ativada com sucesso.');
        } else {
            return redirect()->back()
                ->with('erro', 'Campanha inexistente no sistema.');
        }
    }

    public function dailyLineChartData($id) {
        return DB::table('campaign_logs')
                ->where('campaingn_id', $id)
                ->whereYear('campaign_logs.created_at', Carbon::now()->year)
                ->whereMonth('campaign_logs.created_at', Carbon::now()->month)
                ->groupBy('day')
                ->get([
                    DB::raw('SUM(campaign_logs.impressions) as impressions'),
                    DB::raw('SUM(campaign_logs.clicks) as clicks'),
                    DB::raw('SUM(campaign_logs.revenues) as revenues'),
                    DB::raw('DAY(campaign_logs.created_at) as day')]);
    }
    
    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        $campaingn = Campaingn::with(['user'])->where('id', $id)->first();
        if ($campaingn == null) {
            return back()->with('error'
                            , 'Campaingn não registrada no sistema.');
        } else if ($campaingn->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode excluir esta Campaingn.');
        } else {
            $campaingn->delete();
            return redirect('campaingns');
        }
    }

    private function validar($post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            'brand.required' => 'Insira o nome da marca.',
            'name.min' => 'Nome muito curto.',
            'brand.min' => 'Nome da marca muito curto.',
            'creatives.required' => 'Selecione um Anúncio.',
            'creatives.min' => 'Selecione um Anúncio..',
            'type.in' => 'Tipo de campanha inválido.',
            'type_layout.in' => 'Layout inválido.',
            'ceiling.required' => 'Insira um orçamento diário.',
            'ceiling.numeric' => 'Orçamento inválido.',
            'device.in' => 'Dispositio inválido.',
            'contry.in' => 'País não listado no sistema.'
        );
        $contryCodes = implode(',', array_keys($this->countries()));
        $rules = array(
            'name' => 'required|min:4',
            'brand' => 'required|min:4',
            'creatives' => 'required|array|min:1',
            'type_layout' => 'in:1,2,3,4,5',
            'ceiling' => 'required|numeric',
            'type_layout' => 'in:1,2,3,4,5,6',
            'device' => 'in:1,2',
            'country' => "in:$contryCodes",
        );
        $rules['type'] = 'in:"CPC"';
        if (Auth::user()->hasRole('admin')) {
            $rules['type'] .= ',"CPA"';
        }
        if ($post['type_layout'] != 2) {
            $rules['type'] .= ',"CPM"';
        }
        if (isset($post['type']) && $post['type'] == "CPC") {
            $mensagens['cpc.numeric'] = 'CPC deve ser numérico.';
            $mensagens['cpc.min'] = 'Valor mínimo para CPC: 0.001.';
            $rules['cpc'] = 'numeric|min:0.001';
        } else if (isset($post['type']) && $post['type'] == "CPM") {
            $mensagens['cpm.numeric'] = 'CPM deve ser numérico.';
            $mensagens['cpm.min'] = 'Valor mínimo para CPM: 0.5.';
            $rules['cpm'] = 'numeric|min:0.5';
        }
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function pauseAllCampaigns(Request $request) {
        DB::table('campaingns')
            ->where('user_id', Auth::id())
            ->update(['paused' => true]);
        return redirect('campaingns')
            ->with('success', 'Todas as campanhas foram pausadas.');
    }

    public function pauseConfirm() {
        return view('campaingns.pauseconfirm');
    }

    public function countries() {
        return [
            'AD' => 'Andorra',
            'AE' => 'United Ar',
            'AF' => 'Afghanist',
            'AG' => 'Antigua a',
            'AI' => 'Anguilla',
            'AL' => 'Albania',
            'AM' => 'Armenia',
            'AO' => 'Angola',
            'AQ' => 'Antarctica',
            'AR' => 'Argentina',
            'AS' => 'American',
            'AT' => 'Austria',
            'AU' => 'Australia',
            'AW' => 'Aruba',
            'AX' => 'Aland Isla',
            'AZ' => 'Azerbaija',
            'BA' => 'Bosnia an',
            'BB' => 'Barbados',
            'BD' => 'Banglades',
            'BE' => 'Belgium',
            'BF' => 'Burkina F',
            'BG' => 'Bulgaria',
            'BH' => 'Bahrain',
            'BI' => 'Burundi',
            'BJ' => 'Benin',
            'BL' => 'Saint Bart',
            'BM' => 'Bermuda',
            'BN' => 'Brunei Da',
            'BO' => 'Bolivia, Pl',
            'BQ' => 'Bonaire, S',
            'BR' => 'Brazil',
            'BS' => 'Bahamas',
            'BT' => 'Bhutan',
            'BV' => 'Bouvet Isl',
            'BW' => 'Botswana',
            'BY' => 'Belarus',
            'BZ' => 'Belize',
            'CA' => 'Canada',
            'CC' => 'Cocos (Ke',
            'CD' => 'Congo, Th',
            'CF' => 'Central Af',
            'CG' => 'Congo',
            'CH' => 'Switzerla',
            'CI' => 'Cote D\'ivo',
            'CK' => 'Cook Islan',
            'CL' => 'Chile',
            'CM' => 'Cameroon',
            'CN' => 'China',
            'CO' => 'Colombia',
            'CR' => 'Costa Rica',
            'CU' => 'Cuba',
            'CV' => 'Cabo Ver',
            'CW' => 'Curacao',
            'CX' => 'Christmas',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Rep',
            'DE' => 'Germany',
            'DJ' => 'Djibouti',
            'DK' => 'Denmark',
            'DM' => 'Dominica',
            'DO' => 'Dominica',
            'DZ' => 'Algeria',
            'EC' => 'Ecuador',
            'EE' => 'Estonia',
            'EG' => 'Egypt',
            'EH' => 'Western S',
            'ER' => 'Eritrea',
            'ES' => 'Spain',
            'ET' => 'Ethiopia',
            'FI' => 'Finland',
            'FJ' => 'Fiji',
            'FK' => 'Falkland I',
            'FM' => 'Micronesi',
            'FO' => 'Faroe Isla',
            'FR' => 'France',
            'GA' => 'Gabon',
            'GB' => 'United Ki',
            'GD' => 'Grenada',
            'GE' => 'Georgia',
            'GF' => 'French Gu',
            'GG' => 'Guernsey',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GL' => 'Greenlan',
            'GM' => 'Gambia',
            'GN' => 'Guinea',
            'GP' => 'Guadelou',
            'GQ' => 'Equatorial',
            'GR' => 'Greece',
            'GS' => 'South Ge',
            'GT' => 'Guatemal',
            'GU' => 'Guam',
            'GW' => 'Guinea-Bi',
            'GY' => 'Guyana',
            'HK' => 'Hong Kon',
            'HM' => 'Heard Isla',
            'HN' => 'Honduras',
            'HR' => 'Croatia',
            'HT' => 'Haiti',
            'HU' => 'Hungary',
            'ID' => 'Indonesia',
            'IE' => 'Ireland',
            'IL' => 'Israel',
            'IM' => 'Isle of Ma',
            'IN' => 'India',
            'IO' => 'British Ind',
            'IQ' => 'Iraq',
            'IR' => 'Iran, Isla',
            'IS' => 'Iceland',
            'IT' => 'Italy',
            'JE' => 'Jersey',
            'JM' => 'Jamaica',
            'JO' => 'Jordan',
            'JP' => 'Japan',
            'KE' => 'Kenya',
            'KG' => 'Kyrgyzsta',
            'KH' => 'Cambodia',
            'KI' => 'Kiribati',
            'KM' => 'Comoros',
            'KN' => 'Saint Kitts',
            'KP' => 'Korea, De',
            'KR' => 'Korea, Re',
            'KW' => 'Kuwait',
            'KY' => 'Cayman Is',
            'KZ' => 'Kazakhsta',
            'LA' => 'Lao Peopl',
            'LB' => 'Lebanon',
            'LC' => 'Saint Luci',
            'LI' => 'Liechtens',
            'LK' => 'Sri Lanka',
            'LR' => 'Liberia',
            'LS' => 'Lesotho',
            'LT' => 'Lithuania',
            'LU' => 'Luxembo',
            'LV' => 'Latvia',
            'LY' => 'Libya',
            'MA' => 'Morocco',
            'MC' => 'Monaco',
            'MD' => 'Moldova,',
            'ME' => 'Montene',
            'MF' => 'Saint Mart',
            'MG' => 'Madagasc',
            'MH' => 'Marshall I',
            'MK' => 'Macedoni',
            'ML' => 'Mali',
            'MM' => 'Myanmar',
            'MN' => 'Mongolia',
            'MO' => 'Macao',
            'MP' => 'Northern',
            'MQ' => 'Martiniqu',
            'MR' => 'Mauritani',
            'MS' => 'Montserr',
            'MT' => 'Malta',
            'MU' => 'Mauritius',
            'MV' => 'Maldives',
            'MW' => 'Malawi',
            'MX' => 'Mexico',
            'MY' => 'Malaysia',
            'MZ' => 'Mozambi',
            'NA' => 'Namibia',
            'NC' => 'New Cale',
            'NE' => 'Niger',
            'NF' => 'Norfolk Is',
            'NG' => 'Nigeria',
            'NI' => 'Nicaragua',
            'NL' => 'Netherlan',
            'NO' => 'Norway',
            'NP' => 'Nepal',
            'NR' => 'Nauru',
            'NU' => 'Niue',
            'NZ' => 'New Zeal',
            'OM' => 'Oman',
            'PA' => 'Panama',
            'PE' => 'Peru',
            'PF' => 'French Po',
            'PG' => 'Papua Ne',
            'PH' => 'Philippin',
            'PK' => 'Pakistan',
            'PL' => 'Poland',
            'PM' => 'Saint Pier',
            'PN' => 'Pitcairn',
            'PR' => 'Puerto Ric',
            'PS' => 'Palestine,',
            'PT' => 'Portugal',
            'PW' => 'Palau',
            'PY' => 'Paraguay',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RS' => 'Serbia',
            'RU' => 'Russian F',
            'RW' => 'Rwanda',
            'SA' => 'Saudi Ara',
            'SB' => 'Solomon I',
            'SC' => 'Seychelle',
            'SD' => 'Sudan',
            'SE' => 'Sweden',
            'SG' => 'Singapore',
            'SH' => 'Saint Hele',
            'SI' => 'Slovenia',
            'SJ' => 'Svalbard a',
            'SK' => 'Slovakia',
            'SL' => 'Sierra Leo',
            'SM' => 'San Marin',
            'SN' => 'Senegal',
            'SO' => 'Somalia',
            'SR' => 'Suriname',
            'SS' => 'South Sud',
            'ST' => 'Sao Tome',
            'SV' => 'El Salvado',
            'SX' => 'Sint Maart',
            'SY' => 'Syrian Ara',
            'SZ' => 'Swaziland',
            'TC' => 'Turks and',
            'TD' => 'Chad',
            'TF' => 'French So',
            'TG' => 'Togo',
            'TH' => 'Thailand',
            'TJ' => 'Tajikistan',
            'TK' => 'Tokelau',
            'TL' => 'Timor-Les',
            'TM' => 'Turkmeni',
            'TN' => 'Tunisia',
            'TO' => 'Tonga',
            'TR' => 'Turkey',
            'TT' => 'Trinidad a',
            'TV' => 'Tuvalu',
            'TW' => 'Taiwan, Pr',
            'TZ' => 'Tanzania,',
            'UA' => 'Ukraine',
            'UG' => 'Uganda',
            'UK' => 'United Ki',
            'UM' => 'United States Minor Outlying Islands',
            'US' => 'United States',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekista',
            'VA' => 'Holy See',
            'VC' => 'Saint Vinc',
            'VE' => 'Venezuel',
            'VG' => 'Virgin Isla',
            'VI' => 'Virgin Isla',
            'VN' => 'Viet Nam',
            'VU' => 'Vanuatu',
            'WF' => 'Wallis an',
            'WS' => 'Samoa',
            'YE' => 'Yemen',
            'YT' => 'Mayotte',
            'ZA' => 'South Afri',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        ];
    }
}
