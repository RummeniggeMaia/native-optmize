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
        // $campaingns = [];
        // $db = new IP2Location(Storage::disk(self::DISK)->path("IP-COUNTRY-ISP.BIN"),IP2Location::FILE_IO);
        // $user_ip = $request->ip();

        // $records = $db->lookup('8.8.8.8', IP2Location::ALL);

        // $codigopais= $records['countryCode'];
        // $isp = $records['isp'];

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
            'country' => "in:$contryCodes"
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
            $rules['cpc'] = 'numeric';
        } else if (isset($post['type']) && $post['type'] == "CPM") {
            $mensagens['cpm.numeric'] = 'CPM deve ser numérico.';
            $rules['cpm'] = 'numeric';
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
            '20' => 'Andorra',
            '784' => 'United Ar',
            '4' => 'Afghanist',
            '28' => 'Antigua a',
            '660' => 'Anguilla',
            '8' => 'Albania',
            '51' => 'Armenia',
            '24' => 'Angola',
            '10' => 'Antarctica',
            '32' => 'Argentina',
            '16' => 'American',
            '40' => 'Austria',
            '36' => 'Australia',
            '533' => 'Aruba',
            '248' => 'Aland Isla',
            '31' => 'Azerbaija',
            '70' => 'Bosnia an',
            '52' => 'Barbados',
            '50' => 'Banglades',
            '56' => 'Belgium',
            '854' => 'Burkina F',
            '100' => 'Bulgaria',
            '48' => 'Bahrain',
            '108' => 'Burundi',
            '204' => 'Benin',
            '652' => 'Saint Bart',
            '60' => 'Bermuda',
            '96' => 'Brunei Da',
            '68' => 'Bolivia, Pl',
            '535' => 'Bonaire, S',
            '76' => 'Brazil',
            '44' => 'Bahamas',
            '64' => 'Bhutan',
            '74' => 'Bouvet Isl',
            '72' => 'Botswana',
            '112' => 'Belarus',
            '84' => 'Belize',
            '124' => 'Canada',
            '166' => 'Cocos (Ke',
            '180' => 'Congo, Th',
            '140' => 'Central Af',
            '178' => 'Congo',
            '756' => 'Switzerla',
            '384' => 'Cote D\'ivo',
            '184' => 'Cook Islan',
            '152' => 'Chile',
            '120' => 'Cameroon',
            '156' => 'China',
            '170' => 'Colombia',
            '188' => 'Costa Rica',
            '192' => 'Cuba',
            '132' => 'Cabo Ver',
            '531' => 'Curacao',
            '162' => 'Christmas',
            '196' => 'Cyprus',
            '203' => 'Czech Rep',
            '276' => 'Germany',
            '262' => 'Djibouti',
            '208' => 'Denmark',
            '212' => 'Dominica',
            '214' => 'Dominica',
            '12' => 'Algeria',
            '218' => 'Ecuador',
            '233' => 'Estonia',
            '818' => 'Egypt',
            '732' => 'Western S',
            '232' => 'Eritrea',
            '724' => 'Spain',
            '231' => 'Ethiopia',
            '246' => 'Finland',
            '242' => 'Fiji',
            '238' => 'Falkland I',
            '583' => 'Micronesi',
            '234' => 'Faroe Isla',
            '250' => 'France',
            '266' => 'Gabon',
            '826' => 'United Ki',
            '308' => 'Grenada',
            '268' => 'Georgia',
            '254' => 'French Gu',
            '831' => 'Guernsey',
            '288' => 'Ghana',
            '292' => 'Gibraltar',
            '304' => 'Greenlan',
            '270' => 'Gambia',
            '324' => 'Guinea',
            '312' => 'Guadelou',
            '226' => 'Equatorial',
            '300' => 'Greece',
            '239' => 'South Ge',
            '320' => 'Guatemal',
            '316' => 'Guam',
            '624' => 'Guinea-Bi',
            '328' => 'Guyana',
            '344' => 'Hong Kon',
            '334' => 'Heard Isla',
            '340' => 'Honduras',
            '191' => 'Croatia',
            '332' => 'Haiti',
            '348' => 'Hungary',
            '360' => 'Indonesia',
            '372' => 'Ireland',
            '376' => 'Israel',
            '833' => 'Isle of Ma',
            '356' => 'India',
            '86' => 'British Ind',
            '368' => 'Iraq',
            '364' => 'Iran, Isla',
            '352' => 'Iceland',
            '380' => 'Italy',
            '832' => 'Jersey',
            '388' => 'Jamaica',
            '400' => 'Jordan',
            '392' => 'Japan',
            '404' => 'Kenya',
            '417' => 'Kyrgyzsta',
            '116' => 'Cambodia',
            '296' => 'Kiribati',
            '174' => 'Comoros',
            '659' => 'Saint Kitts',
            '408' => 'Korea, De',
            '410' => 'Korea, Re',
            '414' => 'Kuwait',
            '136' => 'Cayman Is',
            '398' => 'Kazakhsta',
            '418' => 'Lao Peopl',
            '422' => 'Lebanon',
            '662' => 'Saint Luci',
            '438' => 'Liechtens',
            '144' => 'Sri Lanka',
            '430' => 'Liberia',
            '426' => 'Lesotho',
            '440' => 'Lithuania',
            '442' => 'Luxembo',
            '428' => 'Latvia',
            '434' => 'Libya',
            '504' => 'Morocco',
            '492' => 'Monaco',
            '498' => 'Moldova,',
            '499' => 'Montene',
            '663' => 'Saint Mart',
            '450' => 'Madagasc',
            '584' => 'Marshall I',
            '807' => 'Macedoni',
            '466' => 'Mali',
            '104' => 'Myanmar',
            '496' => 'Mongolia',
            '446' => 'Macao',
            '580' => 'Northern',
            '474' => 'Martiniqu',
            '478' => 'Mauritani',
            '500' => 'Montserr',
            '470' => 'Malta',
            '480' => 'Mauritius',
            '462' => 'Maldives',
            '454' => 'Malawi',
            '484' => 'Mexico',
            '458' => 'Malaysia',
            '508' => 'Mozambi',
            '516' => 'Namibia',
            '540' => 'New Cale',
            '562' => 'Niger',
            '574' => 'Norfolk Is',
            '566' => 'Nigeria',
            '558' => 'Nicaragua',
            '528' => 'Netherlan',
            '578' => 'Norway',
            '524' => 'Nepal',
            '520' => 'Nauru',
            '570' => 'Niue',
            '554' => 'New Zeal',
            '512' => 'Oman',
            '591' => 'Panama',
            '604' => 'Peru',
            '258' => 'French Po',
            '598' => 'Papua Ne',
            '608' => 'Philippin',
            '586' => 'Pakistan',
            '616' => 'Poland',
            '666' => 'Saint Pier',
            '612' => 'Pitcairn',
            '630' => 'Puerto Ric',
            '275' => 'Palestine,',
            '620' => 'Portugal',
            '585' => 'Palau',
            '600' => 'Paraguay',
            '634' => 'Qatar',
            '638' => 'Reunion',
            '642' => 'Romania',
            '688' => 'Serbia',
            '643' => 'Russian F',
            '646' => 'Rwanda',
            '682' => 'Saudi Ara',
            '90' => 'Solomon I',
            '690' => 'Seychelle',
            '729' => 'Sudan',
            '752' => 'Sweden',
            '702' => 'Singapore',
            '654' => 'Saint Hele',
            '705' => 'Slovenia',
            '744' => 'Svalbard a',
            '703' => 'Slovakia',
            '694' => 'Sierra Leo',
            '674' => 'San Marin',
            '686' => 'Senegal',
            '706' => 'Somalia',
            '740' => 'Suriname',
            '728' => 'South Sud',
            '678' => 'Sao Tome',
            '222' => 'El Salvado',
            '534' => 'Sint Maart',
            '760' => 'Syrian Ara',
            '748' => 'Swaziland',
            '796' => 'Turks and',
            '148' => 'Chad',
            '260' => 'French So',
            '768' => 'Togo',
            '764' => 'Thailand',
            '762' => 'Tajikistan',
            '772' => 'Tokelau',
            '626' => 'Timor-Les',
            '795' => 'Turkmeni',
            '788' => 'Tunisia',
            '776' => 'Tonga',
            '792' => 'Turkey',
            '780' => 'Trinidad a',
            '798' => 'Tuvalu',
            '158' => 'Taiwan, Pr',
            '834' => 'Tanzania,',
            '804' => 'Ukraine',
            '800' => 'Uganda',
            '826' => 'United Ki',
            '581' => 'United St',
            '840' => 'United St',
            '858' => 'Uruguay',
            '860' => 'Uzbekista',
            '336' => 'Holy See',
            '670' => 'Saint Vinc',
            '862' => 'Venezuel',
            '92' => 'Virgin Isla',
            '850' => 'Virgin Isla',
            '704' => 'Viet Nam',
            '548' => 'Vanuatu',
            '876' => 'Wallis an',
            '882' => 'Samoa',
            '887' => 'Yemen',
            '175' => 'Mayotte',
            '710' => 'South Afri',
            '894' => 'Zambia',
            '716' => 'Zimbabwe',
        ];
    }
}
