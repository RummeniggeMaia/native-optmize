<?php

namespace App\Http\Controllers;

use App\WidgetCampaingn;
use App\Http\Requests;
use Illuminate\Http\Request;

class WidgetCampaingnController extends Controller
{
     /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $widget_campaingns = WidgetCampaingn::all();
        return view('widget_campaingns.index',compact('widget_campaingns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('widget_campaingns.create');
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $widget_campaingn = $request->all();
        WidgetCampaingn::create($widget_campaingn);
        return redirect('api/widget_campaingns');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $widget_campaingn = WidgetCampaingn::find($id);
        return view('widget_campaingns.show',compact('widget_campaingn')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $widget_campaingn = WidgetCampaingn::find($id);
        return view('widget_campaingns.edit',compact('widget_campaingn'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $widget_campaingnUpdate = $request->all();
        $widget_campaingn = WidgetCampaingn::find($id);
        $widget_campaingn->update($widget_campaingnUpdate);
        return redirect('api/widget_campaingns');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        WidgetCampaingn::find($id)->delete();
        return redirect('api/widget_campaingns');
    }

}
