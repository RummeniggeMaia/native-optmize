<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Creative;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use Illuminate\Support\Facades\Storage;

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
        $creatives = Creative::where('owner', Auth::id())
                        ->orderBy('name', 'asc')->paginate(5);
        return view('creatives.index', compact('creatives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $categories = Category::where('fixed', true)
                        ->orderBy('name', 'asc')->get();
        $myCategories = Category::where('owner', Auth::id())
                        ->orderBy('name', 'asc')->get();
        return view('creatives.create', array(
            'categories' => $categories,
            'myCategories' => $myCategories
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $creative = Creative::find($id);
        if ($creative == null) {
            return back()->with('error'
                            , 'Creative não registrado no sistema.');
        } else if ($creative->owner != Auth::id()) {
            return back()->with('error'
                            , 'Não pode exibir os dados deste Creative.');
        } else {
            $category = Category::find($creative->related_category);
            return view('creatives.show', compact('creative'))
                            ->with('category', $category);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $creative = Creative::find($id);
        if ($creative == null) {
            return back()->with('error'
                            , 'Creative não registrado no sistema.');
        } else if ($creative->owner != Auth::id()) {
            return back()->with('error'
                            , 'Não pode editar os dados deste Creative.');
        } else {
            $categories = Category::where('fixed', true)
                            ->orderBy('name', 'asc')->get();
            $myCategories = Category::where('owner', Auth::id())
                            ->orderBy('name', 'asc')->get();
            return view('creatives.update'
                    , compact('creative')
                    , array('categories' => $categories, 'myCategories' => $myCategories)
            );
        }
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $validacao = $this->validar($post);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            $post['owner'] = Auth::id();

//            if ($request->hasFile('image')) {
//                $image = $request->file('image');
//                $image_name = $image->getClientOriginalName();
//
//                $image->storeAs('img/', $image_name, 'native_storage');
//
//                $image_path = $this->compress_image($image_name);
//                $post['image'] = $image_path;
//            }
            Creative::create($post);
            return redirect('creatives')
                            ->with('success'
                                    , 'Creative cadastrado com sucesso.');
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
        $validacao = $this->validar($post);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            $creative = Creative::find($id);
            if ($creative == null) {
                return back()->with('error'
                                , 'Creative não registrado no sistema.');
            } else if ($creative->owner != Auth::id()) {
                return back()->with('error'
                                , 'Não pode atualizar os dados deste Creative.');
            } else {
                $creative->update($post);
                return redirect('creatives')
                                ->with('success'
                                        , 'Creative atualizado com sucesso.');
            }
        }
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        $creative = Creative::find($id);
        if ($creative == null) {
            return back()->with('error'
                            , 'Creative não registrado no sistema.');
        } else if ($creative->owner != Auth::id()) {
            return back()->with('error'
                            , 'Não pode excluir este Creative.');
        } else {
            $creative->delete();
            return redirect('creatives')
                            ->with('success', 'Creative excluído com sucesso.');
        }
    }

    private function validar($post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            //'name.unique' => 'Já existe um creative com este nome.',
            'name.min' => 'Nome muito curto.',
            'url.regex' => 'URL inválido.',
            'related_category.required' => 'Selecione uma Category',
            'image.required' => 'Selecione uma imagem.'
        );
        $rules = array(
            'name' => 'required|min:4',
            'url' => "regex:/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$/",
            'related_category' => 'required',
            'image' => 'required'
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function compress_image($image_name) {
        // setting
        $setting = array(
            'directory' => Storage::disk('native_storage')->url("img/compressed"), // directory file compressed output
            'file_type' => array(// file format allowed
                'image/jpeg',
                'image/png'
            )
        );

        $image_path = Storage::disk('native_storage')->url("img/{$image_name}");

        // create object
        $ImgCompressor = new ImgCompressor($setting);

        // run('STRING original file path', 'output file type', INTEGER Compression level: from 0 (no compression) to 9);
        // example level = 2 same quality 80%, level = 7 same quality 30% etc
        $result = $ImgCompressor->run($image_path, "compressed-{$image_name}.jpg", 'jpg', 1);

        $compressed_image_name = $result['data']['compressed']['name'];
        $compressed_image_path = Storage::disk('native_storage')->url("img/compressed/{$compressed_image_name}");

        return $compressed_image_path;
    }

}
