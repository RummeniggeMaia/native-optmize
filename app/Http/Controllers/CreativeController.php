<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Creative;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreativeController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $creatives = Creative::all()->where('owner', Auth::id());
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
        $categories = Category::all();
        return view('creatives.update', compact('creative'))
                ->with('categories', $categories);
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $post['owner'] = Auth::id();
        Creative::create($post);
        return redirect('creatives');
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
        return redirect('creatives');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        Creative::find($id)->delete();
        return redirect('creatives');
    }

}
