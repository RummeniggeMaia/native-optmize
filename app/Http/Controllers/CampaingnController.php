<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Campaingn;
use App\Creative;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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
        $campaingns = Campaingn::where('user_id', Auth::id())
                        ->orderBy('name', 'asc')->paginate(5);
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
                })->addColumn('type_layout', function($campaingn) {
                    return array(
                            '0' =>  '-',
                            '1' => 'Native',
                            '2' => 'Smart Link',
                            '3' => 'Banner Square (300x250)',
                            '4' => 'Banner Mobile (300x100)',
                            '5' => 'Banner Footer (928x244)',
                        )[$campaingn->type_layout];
                })->rawColumns(
                        ['edit', 'show', 'delete']
                )->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $creatives = Creative::where([
                'user_id'=>Auth::id(),
                'type_layout'=>1
                ])->orderBy('name', 'asc')->get();
        return view('campaingns.create')->with([
            'creatives' => $creatives,
            'types'=>$this->types]);
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
                $campaingn = Campaingn::create($post);
                $campaingn->creatives()->sync($post['creatives']);
                DB::commit();
                return redirect('campaingns')
                                ->with('success', 'Campaingn cadastrada com sucesso.');
            } catch (Exception $e) {
                DB::rollBack();
                return redirect('campaingns')
                                ->with('error', 'Erro ao cadastrar Campaingn.');
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
                            , 'Campaingn não registrada no sistema.');
        } else if ($campaingn->user->id != Auth::id()) {
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
        } else if ($campaingn->user->id != Auth::id()) {
            return null;
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
            return view('campaingns.create')
                ->with(['creatives' => $creatives, 'types'=>$this->types])
                ->withErrors($validacao);
        } else {
            $campaingn = Campaingn::with(['user'])->where('id', $id)->first();
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
                } catch (Exception $e) {
                    DB::rollBack();
                }
                return redirect('campaingns');
            }
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
        );
        $rules = array(
            'name' => 'required|min:4',
            'brand' => 'required|min:4',
            'creatives' => 'required|array|min:1',
            'type_layout' => 'in:1,2,3,4,5',
        );
        if ($post['type_layout'] == 2) {
            $rules['type'] = 'in:"CPA","CPC"';
        } else {
            $rules['type'] = 'in:"CPA","CPC","CPM"';
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

}
