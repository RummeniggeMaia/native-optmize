<?php

namespace App\Http\Controllers;

use App\Creative;
use App\Category;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreativeController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index() {
        $creatives = Creative::all();
        return view('creatives.index', compact('creatives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $categories = Category::all();
        return view('creatives.create')->with('categories', $categories);
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $creative = $request->all();
        Creative::create($creative);
        return redirect('api/creatives');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $creative = Creative::find($id);
        $category = Category::find($creative->related_category);
        return view('creatives.show', compact('creative'))->
                        with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $creative = Creative::find($id);
        return view('creatives.update', compact('creative'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $creativeUpdate = $request->all();
        $creative = Creative::find($id);
        $creative->update($creativeUpdate);
        return redirect('api/creatives');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        Creative::find($id)->delete();
        return redirect('api/creatives');
    }

}
