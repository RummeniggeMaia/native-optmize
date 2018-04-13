<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index() {
        $categories = Category::where('fixed', true)
                        ->orderBy('name', 'asc')->paginate(5);
        return view('categories.index', compact('categories'));
    }

    public function indexDataTable() {
        $categories = DB::table('categories')->where('user_id', Auth::id())->get();
        return Datatables::of($categories)->addColumn('edit', function($category) {
                    return view('comum.button_edit', [
                        'id' => $category->id,
                        'route' => 'categories.edit'
                    ]);
                })->addColumn('show', function($category) {
                    return view('comum.button_show', [
                        'id' => $category->id,
                        'route' => 'categories.show'
                    ]);
                })->addColumn('delete', function($category) {
                    return view('comum.button_delete', [
                        'id' => $category->id,
                        'route' => 'categories.destroy'
                    ]);
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
        return view('categories.create');
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
            $post['user_id'] = Auth::id();
            $post['fixed'] = Auth::user()->hasRole('admin');
            Category::create($post);
            return redirect('categories');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $category = Category::find($id);
        if ($category->fixed && Auth::user()->hasRole('user')) {
            return $this->index();
        }
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $category = Category::find($id);
        if ($category->fixed && Auth::user()->hasRole('user')) {
            return $this->index();
        }
        return view('categories.update', compact('category'));
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
                            ->withErrors($v)
                            ->withInput();
        } else {
            $category = Category::find($id);
            $category->update($post);
            return redirect('categories');
        }
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        $category = Category::find($id);
        if ($category->fixed && Auth::user()->hasRole('user')) {
            return redirect('categories');
        }
        $category->delete();
        return redirect('categories');
    }

    private function validar($post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            'name.min' => 'Nome muito curto.'
        );
        $rules = array(
            'name' => 'required|min:4',
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

}
