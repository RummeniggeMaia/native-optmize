<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Creative;
use App\Widget;
use App\Click;
use App\Category;
use App\CreativeLog;
use App\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Providers\ImgCompressor;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CreativeController extends Controller {

    // const DISK = "native_storage";
    const DISK = "public";

    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $creatives = Creative::where('user_id', Auth::id())
                        ->orderBy('name', 'asc')->paginate(5);
        return view('creatives.index', compact('creatives'));
    }

    public function indexDataTable() {
        $creatives = DB::table('creatives')->where('user_id', Auth::id())->get();
        return Datatables::of($creatives)->addColumn('edit', function($creative) {
                    return view('comum.button_edit', [
                        'id' => $creative->id,
                        'route' => 'creatives.edit'
                    ]);
                })->addColumn('show', function($creative) {
                    return view('comum.button_show', [
                        'id' => $creative->id,
                        'route' => 'creatives.show'
                    ]);
                })->addColumn('delete', function($creative) {
                    return view('comum.button_delete', [
                        'id' => $creative->id,
                        'route' => 'creatives.destroy'
                    ]);
                })->editColumn('image', function($creative) {
                    return view('comum.image', [
                        'image' => $creative->image
                    ]);
                })->editColumn('status', function($creative) {
                    if ($creative->status) {
                        return view('comum.status_on');
                    } else {
                        return view('comum.status_off');
                    }
                })->addColumn('type_layout', function($creative) {
                    return array(
                            '0' => '-',
                            '1'=>'Native',
                            '2'=>'Smart Link',
                            '3'=>'Banner Square (300x250)',
                            '4'=>'Banner Mobile (300x100)',
                            '5'=>'Banner Footer (928x244)',
                            '6'=>'Vídeo',
                        )[$creative->type_layout];
                })->addColumn('duplicate', function($creative) {
                    return view('comum.button_duplicate', [
                        'id' => $creative->id,
                        'route' => 'creatives.duplicate'
                    ]);
                })->rawColumns(
                        ['edit', 'show', 'delete', 'image', 'status', 'duplicate']
                )->make(true);
    }

    public function imagesDataTable($creativeId) {
        $images= DB::table('images')->where('creative_id', $creativeId)->get();
        return Datatables::of($images)->addColumn('delete', function($image) {
                    return view('comum.button_delete', [
                        'id' => $image->id,
                        'route' => 'creatives.images.destroy'
                    ]);
                })->editColumn('path', function($image) {
                    return view('comum.image', [
                        'image' => $image->path
                    ]);
                })->rawColumns(
                        ['delete', 'path']
                )->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $categories = Category::where('fixed', true)
                        ->orderBy('name', 'asc')->get();
        return view('creatives.create', array(
            'categories' => $categories
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $creative = Creative::with(['category', 'user'])
                        ->where('id', $id)->first();
        if ($creative == null) {
            return back()->with('error'
                            , 'Anúncio não registrado no sistema.');
        } else if ($creative->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode exibir os dados deste Anúncio.');
        } else {
            $clicks = CreativeLog::where('creative_id', $creative->id)
                    ->sum('clicks');
            $impressions = CreativeLog::where('creative_id', $creative->id)
                    ->sum('impressions');
            return view('creatives.show', compact('creative'))
                            ->with([
                                'clicks' => $clicks,
                                'impressions' => $impressions]);
        }
    }

    public function clicksDataTable($id) {
        $clickList = Click::with(['widget'])->where('creative_id', $id)->get();
        return Datatables::of($clickList)->editColumn('widget.name', function($click){
            return $click->widget == null ? "-" : $click->widget->name;
        })->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $creative = Creative::with(['category', 'user'])
                        ->where('id', $id)->first();
        if ($creative == null) {
            return back()->with('error'
                            , 'Anúncio não registrado no sistema.');
        } else if ($creative->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode editar os dados deste Anúncio.');
        } else {
            $categories = Category::where('fixed', true)
                            ->orderBy('name', 'asc')->get();
            return view('creatives.update'
                    , compact('creative')
                    , array('categories' => $categories)
            );
        }
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $post = $request->all();
        $validacao = $this->validar($post, false);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            $post['user_id'] = Auth::id();
            $post['hashid'] = Hash::make(Auth::id() . "hash" . Carbon::now()->toDateTimeString());

            /**
             * TODO - Implementar remocao de imagens do armazenamento caso haja erro.
             */
            $imagesCreated = [];
            DB::beginTransaction();
            try {
                if ($request->hasFile('image')) {
                    $image = $request->file('image')[0];
                    $pathToImage = $image->store('img/', self::DISK);
                    $image_path = $this->compress_image($pathToImage, $image->hashName());
                    
                    $post['image'] = "storage/" . $image_path;
                    $post['status'] = true;
                    $creative = Creative::create($post);
                    foreach ($request->file('image') as $img) {
                        $pathToImage = $img->store('img/', self::DISK);
                        $hashName = $img->hashName();
                        $image_path = $this->compress_image($pathToImage, $hashName);
                        Image::create([
                            'name' => $hashName,
                            'original_name' => $img->getClientOriginalName(),
                            'path' => "storage/" . $image_path,
                            'creative_id' => $creative->id
                        ]);
                    }
                    DB::commit();
                } else {
                    throw new Exception("Não contém imagens. ");
                }
            } catch (Exception $ex) {
                DB::rollBack();
                return redirect('creatives')
                                ->with('error', 'Erro ao salvar anúncio: ' . $ex->getMessage());
            }
            return redirect('creatives')
                            ->with('success'
                                    , 'Anúncio cadastrado com sucesso.');
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
        $validacao = $this->validar($post, true);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            $creative = Creative::find($id);
            if ($creative == null) {
                return back()->with('error'
                                , 'Anúncio não registrado no sistema.');
            } else if ($creative->user_id != Auth::id()) {
                return back()->with('error'
                                , 'Não pode atualizar os dados deste Anúncio.');
            } else {
                DB::beginTransaction();
                try {
                    if ($request->hasFile('image')) {
                        $image = $request->file('image')[0];
                        $pathToImage = $image->store('img/', self::DISK);
                        $image_path = $this->compress_image($pathToImage, $image->hashName());
                        
                        $post['image'] = "storage/" . $image_path;
                        foreach ($request->file('image') as $img) {
                            $pathToImage = $img->store('img/', self::DISK);
                            $hashName = $img->hashName();
                            $image_path = $this->compress_image($pathToImage, $hashName);
                            Image::create([
                                'name' => $hashName,
                                'original_name' => $img->getClientOriginalName(),
                                'path' => "storage/" . $image_path,
                                'creative_id' => $creative->id
                            ]);
                        }
                    }
                    $creative->update($post);
                    DB::commit();
                    return redirect('creatives')
                                    ->with('success'
                                            , 'Anúncio atualizado com sucesso.');
                } catch (Exception $ex) {
                    return redirect()->back()
                        ->with('success'
                                , 'Anúncio atualizado com sucesso.');
                    }
            }
        }
    }

    public function duplicar($id) {
        $creative = Creative::with(['images'])->find($id)->first();
        if ($creative == null) {
            return back()->with('error'
                            , 'Creative não registrado no sistema.');
        } else if ($creative->user_id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode atualizar os dados deste Creative.');
        } else {
            try {
                $clone = $creative->replicate();
                $clone->save();
            } catch (Exception $ex) {
                return redirect('creatives')
                    ->with('error'
                            , 'Anúncio duplicado com sucesso.');
            }
            return redirect()->back()
                            ->with('success'
                                    , 'Não pode duplicar anúncio.');
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
        } else if ($creative->user_id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode excluir este Creative.');
        } else {
            $creative->delete();
            return redirect('creatives')
                            ->with('success', 'Creative excluído com sucesso.');
        }
    }

    public function destroyImage($id) {
        /**
         * TODO: Verificar se a imagem pertence a um creative do
         * usuario q ta solicitando a requisicao.
         */
        $image = Image::find($id);
        if ($image == null) {
            return back()->with('error'
                            , 'Imagem não registrada no sistema.');
        } else {
            $image->delete();
            return back()->with('success', 
                'Imagem excluída com sucesso.');
        }
    }

    private function validar($post, $edit) {
        $mensagens = array(
            'brand.required' => 'Insira uma brand.',
            'brand.min' => 'Brand muito curto.',
            'name.required' => 'Insira um nome.',
            //'name.unique' => 'Já existe um creative com este nome.',
            'name.min' => 'Nome muito curto.',
            //'url.regex' => 'URL inválido.',
            'category_id.required' => 'Selecione uma Category',
            'image.required' => 'Selecione uma imagem.',
            'image.dimensions' => 'Dimensões da imagem não coincidem com o layout escolhido.',
            'image.mimes' => "Tipos permitidos: jpeg,png,jpg,gif",
            'status.in' => 'Status inválido.',
            'type_layout.in' => 'Layout inválido.',
        );

        $dimensions = "";
        if ($post['type_layout'] == '3') {
            $dimensions = "width=300,height=250";
        } else if ($post['type_layout'] == '4') {
            $dimensions = "width=300,height=100";
        } else if ($post['type_layout'] == '5') {
            $dimensions = "width=928,height=244";
        }

        $rules = array(
            'brand' => 'required|min:4',
            'name' => 'required|min:4',
            //'url' => "regex:/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$/",
            'category_id' => 'required',
            'image.*' => "dimensions:$dimensions". ($edit ? '' : '|required|mimes:jpeg,png,jpg,gif'),
            'status' => 'in:0,1',
            'type_layout' => 'in:1,2,3,4,5,6',
        );

        if(empty($dimensions)) {
            unset($rules['image']);
        }
        
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function compress_image($imgPath, $image_name)
    {
        // $videos_extension = array('avi','mov','wmv','mp4','3gp','3g2','flv','mkv','rm','webp','mpeg4');
        $videos_extension = array('mp4');

        $file_extension = explode(".", $image_name);
        $file_extension = strtolower(end($file_extension));

        if(in_array($file_extension, $videos_extension)) {
            return $imgPath;
        } else {
            $path = "img/compressed";
            //Cria diretorio storage caso n exista no disco
            if (!File::exists(Storage::disk(self::DISK)->path($path))) {
                File::makeDirectory(Storage::disk(self::DISK)->path($path), 0775, true);
            }
            // setting
            $setting = array(
                // 'directory' => "C:/xampp7/htdocs/native-optimize/storage/app/public/img/compressed", // directory file compressed output
                'directory' => Storage::disk(self::DISK)->path($path), // directory file compressed output
                'file_type' => array(// file format allowed
                    'image/jpeg',
                    'image/png'
                )
            );

            $image_path = Storage::disk(self::DISK)->path($imgPath);
            //Acessando informacoes da imagem
            $im_info = getImageSize($image_path);
            $im_type = $im_info['mime'];

            if ($im_type == 'image/gif') {
                $compressed_path = Storage::disk(self::DISK)->path($path);
                $compressed_image_path = $path . "/" . $image_name;
                if (Storage::disk(self::DISK)->exists("img/compressed/{$image_name}")) {
                    Storage::disk(self::DISK)->delete("img/compressed/{$image_name}");
                }
                Storage::disk(self::DISK)->copy("img/{$image_name}", "img/compressed/{$image_name}");
                return $compressed_image_path;
            } else {
                // create object
                $ImgCompressor = new ImgCompressor($setting);
    
                // run('STRING original file path', 'output file type', INTEGER Compression level: from 0 (no compression) to 9);
                // example level = 2 same quality 80%, level = 7 same quality 30% etc
                $result = $ImgCompressor->run($image_path, "compressed-{$image_name}.jpg", 'jpg', 1);
    
                // return Storage::disk(self::DISK)->path($path . "/" . "compressed-{$image_name}.jpg");
                $compressed_image_name = $result['data']['compressed']['name'];
                $compressed_image_path = $path . "/" . $compressed_image_name;
    
                return $compressed_image_path;
            }
        }
    }

    public function duplicateCreative($id) {
        $creative = Creative::with(['user'])->where('id', $id)->first();
        if ($creative == null) {
            return back()->with('error'
                            , 'Anúncio não registrado no sistema.');
        } else if ($creative->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode duplicar este Anúncio.');
        } else {
            $clone = $creative->replicate();
            $clone->save();
            return redirect('creatives')
                ->with('success', 'Anúncio duplicado com sucesso.');
        }
    }

}
