<?php

namespace App\Http\Controllers;

use App\Campaingn;
use App\Http\Requests;
use Illuminate\Http\Request;

class CampaingnController extends Controller
{
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $campaingns = Campaingn::all();
        return view('campaingns.index',compact('campaingns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('campaingns.create');
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $campaingn = $request->all();
        Campaingn::create($campaingn);
        return redirect('api/campaingns');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $campaingn = Campaingn::find($id);
        return view('campaingns.show',compact('campaingn')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $campaingn = Campaingn::find($id);
        return view('campaingns.edit',compact('campaingn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $campaingnUpdate = $request->all();
        $campaingn = Campaingn::find($id);
        $campaingn->update($campaingnUpdate);
        return redirect('api/campaingns');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Campaingn::find($id)->delete();
        return redirect('api/campaingns');
    }
}
