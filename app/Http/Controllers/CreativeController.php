<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Creative;
use App\Widget;
use App\Click;
use App\Category;
use App\CreativeLog;
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
                })->editColumn('status', function($user) {
                    if ($user->status) {
                        return view('comum.status_on');
                    } else {
                        return view('comum.status_off');
                    }
                })->addColumn('type_layout', function($widget) {
                    return array(
                            '0' => '-',
                            '1'=>'Native',
                            '2'=>'Smart Link',
                            '3'=>'Banner Square (300x250)',
                            '4'=>'Banner Mobile (300x100)',
                            '5'=>'Banner Footer (928x244)',
                        )[$widget->type_layout];
                })->rawColumns(
                        ['edit', 'show', 'delete', 'image', 'status']
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
                            , 'Creative não registrado no sistema.');
        } else if ($creative->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode exibir os dados deste Creative.');
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
        $clickList = Click::where('creative_id', $id)->with(['widget'])->get();
        return Datatables::of($clickList)->make(true);
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
                            , 'Creative não registrado no sistema.');
        } else if ($creative->user->id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode editar os dados deste Creative.');
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

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $pathToImage = $image->store('img/', self::DISK);
                $image_path = $this->compress_image($pathToImage, $image->hashName());

                $post['image'] = "storage/" . $image_path;
            }
//            if (Auth::user()->hasRole('admin')) {
            $post['status'] = true;
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
        $validacao = $this->validar($post, true);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            $creative = Creative::find($id);
            if ($creative == null) {
                return back()->with('error'
                                , 'Creative não registrado no sistema.');
            } else if ($creative->user_id != Auth::id()) {
                return back()->with('error'
                                , 'Não pode atualizar os dados deste Creative.');
            } else {
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $pathToImage = $image->store('img/', self::DISK);
                    $image_path = $this->compress_image($pathToImage, $image->hashName());

                    $post['image'] = "storage/" . $image_path;
                }
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
        } else if ($creative->user_id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode excluir este Creative.');
        } else {
            $creative->delete();
            return redirect('creatives')
                            ->with('success', 'Creative excluído com sucesso.');
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
            'image' => "dimensions:$dimensions". ($edit ? '' : '|required'),
            'status' => 'in:0,1',
            'type_layout' => 'in:1,3,4,5',
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function compress_image($imgPath, $image_name) {

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
