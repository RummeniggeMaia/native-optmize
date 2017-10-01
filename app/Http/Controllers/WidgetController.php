<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Campaingn;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WidgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $widgets = Widget::all()->where('owner', Auth::id());
        return view('widgets.index', compact('widgets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $campaingns = Campaingn::all()->where('owner', Auth::id());
        return view('widgets.create')->with(['campaingns' => $campaingns]);
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
        DB::beginTransaction();
        try {
            $post['owner'] = Auth::id();
            $widget = Widget::create($post);
            $widget->campaingns()->sync($post['campaingns']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect('widgets');
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
        return view('widgets.show', compact('widget'));
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
        $campaingns = Campaingn::all()->where('owner', Auth::id());
        return view('widgets.update', compact('widget'))
                    ->with('campaingns', $campaingns);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $post = $request->all();
        DB::beginTransaction();
        try {
            $widget = Widget::find($id);
            $widget->update($post);
            $widget->campaingns()->sync($post['campaingns']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
        return redirect('widgets');
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
        return redirect('widgets');
    }
}
