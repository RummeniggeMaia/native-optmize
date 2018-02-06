<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Campaingn;
use App\Creative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CampaingnController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $campaingns = Campaingn::where('user_id', Auth::id())
                        ->orderBy('name', 'asc')->paginate(5);
        return view('campaingns.index', compact('campaingns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $creatives = Creative::where('user_id', Auth::id())
                        ->orderBy('name', 'asc')->get();
        return view('campaingns.create')->with(['creatives' => $creatives]);
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $validacao = $this->validar($post);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validacao);
        } else {
            DB::beginTransaction();
            try {
                $post['user_id'] = Auth::id();
                $post['hashid'] = Hash::make(Auth::id() . "hash" . Carbon::now()->toDateTimeString());
                
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
                            , 'Não pode exibir os dados desta Campaingn.');
        } else {
            return view('campaingns.show', compact('campaingn'));
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
            $creatives = Creative::all()->where('user_id', Auth::id());
            return view('campaingns.update', compact('campaingn'))
                            ->with('creatives', $creatives);
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
            return redirect()->back()
                            ->withInput()
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
            'creatives.required' => 'Selecione ao menos um Creative.',
            'creatives.min' => 'Selecione ao menos um Creative.'
        );
        $rules = array(
            'name' => 'required|min:4',
            'brand' => 'required|min:4',
            'creatives' => 'required|array|min:1'
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

}
