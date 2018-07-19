<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Campaingn;
use App\Creative;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Providers\IP2Location;

class CampaingnController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
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
                ->with(['creatives' => $creatives, 'types'=>$this->types])
                ->withErrors($validacao);
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
        $campaingn = Campaingn::with(['user'])->where('id', $id)->first();
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
                                'creatives'=>$creatives,
                                'types'=>$this->types]);
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
        $campaingn = Campaingn::with(['user'])->where('id', $id)->first();
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
                ->with(['creatives' => $creatives, 'types'=>$this->types])
                ->withErrors($validacao);
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
        );
        $rules = array(
            'name' => 'required|min:4',
            'brand' => 'required|min:4',
            'creatives' => 'required|array|min:1',
            'type_layout' => 'in:1,2,3,4,5',
            'ceiling' => 'required|numeric',
            'type_layout' => 'in:1,2,3,4,5,6',
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
        // $db = new IP2Location(Storage::disk(self::DISK)->path("IP-COUNTRY-ISP.BIN"),IP2Location::FILE_IO);
        // $user_ip = $request->ip();

        // $records = $db->lookup($user_ip,IP2Location::ALL);

        // $codigopais= $records['countryCode'];
        // $isp = $records['isp'];
    }
}
