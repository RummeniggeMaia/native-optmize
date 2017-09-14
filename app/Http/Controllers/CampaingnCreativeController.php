<?php

namespace App\Http\Controllers;

use App\CampaingnCreative;
use App\Http\Requests;
use Illuminate\Http\Request;

class CampaingnCreativeController extends Controller
{
     /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $campaingn_creatives = CampaingnCreative::all();
        return view('campaingns_creatives.index', compact('campaingn_creatives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('campaingn_creatives.create');
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $campaingn_creative = $request->all();
        CampaingnCreative::create($campaingn_creative);
        return redirect('api/campaingn_creatives');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $campaingn_creative = CampaingnCreative::find($id);
        return view('campaingn_creatives.show',compact('campaingn_creative')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $campaingn_creative = CampaingnCreative::find($id);
        return view('campaingn_creatives.edit',compact('campaingn_creative'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $campaingnCreativeUpdate = $request->all();
        $campaingn_creative = CampaingnCreative::find($id);
        $campaingn_creative->update($campaingnCreativeUpdate);
        return redirect('api/campaingn_creatives');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        CampaingnCreative::find($id)->delete();
        return redirect('api/campaingn_creatives');
    }
}
