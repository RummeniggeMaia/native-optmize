<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Creative;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Providers\ImgCompressor;

class CreativeController extends Controller {
    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $creatives = Creative::where(
                        'owner', Auth::id())->orderBy('name', 'asc')->paginate(5);
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
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        } else {
            $post['owner'] = Auth::id();
            
            if ($request->hasFile('image')){
                $image = $request->file('image');
                $image_name = $image->getClientOriginalName();

                $image->storeAs('img/',$image_name,'teste');

                $image_path = compress_image($image_name);
                $post['image'] = $image_path;
            }

            Creative::create($post);
            return redirect('creatives');
        }
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
            $creativeUpdate = $request->all();
            $creative = Creative::find($id);
            $creative->update($creativeUpdate);
            return redirect('creatives');
        }
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

    private function validar($post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            //'name.unique' => 'Já existe um creative com este nome.',
            'name.min' => 'Nome muito curto.',
            'url.regex' => 'URL inválido.'
        );
        $rules = array(
            'name' => 'required|min:4',
            'url' => 'regex:/^((http[s]?):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/'
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function compress_image($image_name) {
        // setting
        $setting = array(
           'directory' => Storage::disk('teste')->url("img/compressed") , // directory file compressed output
           'file_type' => array( // file format allowed
             'image/jpeg',
             'image/png'
           )
        );
        
        $image_path = Storage::disk('teste')->url("img/{$image_name}");
        
        // create object
        $ImgCompressor = new ImgCompressor($setting);
        
        // run('STRING original file path', 'output file type', INTEGER Compression level: from 0 (no compression) to 9);
        // example level = 2 same quality 80%, level = 7 same quality 30% etc
        $result = $ImgCompressor->run($image_path, "compressed-{$image_name}.jpg",'jpg', 1); 
        
        $compressed_image_name = $result['data']['compressed']['name'];
        $compressed_image_path = Storage::disk('teste')->url("img/compressed/{$compressed_image_name}");

        return $compressed_image_path;
    }

}
