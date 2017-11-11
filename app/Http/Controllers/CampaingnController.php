<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Campaingn;
use App\Creative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Validator;

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
        $campaingns = Campaingn::where('owner', Auth::id())->orderBy('name', 'asc')->paginate(5);
        return view('campaingns.index', compact('campaingns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $creatives = Creative::all()->where('owner', Auth::id());
        return view('campaingns.create')->with(['creatives' => $creatives]);
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors($v);
        } else {
            DB::beginTransaction();
            try {
                $post['owner'] = Auth::id();
                $campaingn = Campaingn::create($post);
                $campaingn->creatives()->sync($post['creatives']);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
            return redirect('campaingns');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $campaingn = Campaingn::find($id);
        return view('campaingns.show', compact('campaingn'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $campaingn = Campaingn::find($id);
        $creatives = Creative::all()->where('owner', Auth::id());
        return view('campaingns.update', compact('campaingn'))
                        ->with('creatives', $creatives);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $post = $request->all();
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors($v);
        } else {
            DB::beginTransaction();
            try {
                $campaingn = Campaingn::find($id);
                $campaingn->update($post);
                $campaingn->creatives()->sync($post['creatives']);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
            return redirect('campaingns');
        }
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        Campaingn::find($id)->delete();
        return redirect('campaingns');
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
