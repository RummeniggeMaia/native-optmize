<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryController extends Controller
{
     /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = Category::where('fixed', false)
                ->orderBy('name', 'asc')->paginate(5);
        return view('categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $post = $request->all();
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withInput()
                            ->withErrors($v);
        } else {
            $post['owner'] = Auth::id();
            $post['fixed'] = false;
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
    public function show($id)
    {
        $category = Category::find($id);
        if ($category->fixed) {
            return $this->index();
        }
        return view('categories.show',compact('category')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        if ($category->fixed) {
            return $this->index();
        }
        return view('categories.update',compact('category'));
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
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        } else {
            $categoryUpdate = $request->all();
            $category = Category::find($id);
            if ($category->fixed) {
                return $this->index();
            }
            $category->update($categoryUpdate);
            return redirect('categories');
        }
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        Category::find($id)->delete();
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
