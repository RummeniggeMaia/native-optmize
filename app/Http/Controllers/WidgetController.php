<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Http\Requests;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $widgets = Widget::all();
        return view('widgets.index',compact('widgets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('widgets.create');
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $widget = $request->all();
        Widget::create($widget);
        return redirect('api/widgets');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $widget = Widget::find($id);
        return view('widgets.show',compact('widget')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $widget = Widget::find($id);
        return view('widgets.edit',compact('widget'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $widgetUpdate = $request->all();
        $widget = Widget::find($id);
        $widget->update($widgetUpdate);
        return redirect('api/widgets');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Widget::find($id)->delete();
        return redirect('api/widgets');
    }
}
