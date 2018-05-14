<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Campaingn;
use App\Creative;
use App\Http\Requests;
use App\CreativeLog;
use App\Click;
use App\Postback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Providers\Template;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class WidgetController extends Controller {

    // const DISK = "local";
    const DISK = "public";

    public function __construct() {
        $this->middleware('auth');
    }

    /*
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index() {
        //$widgets = Widget::where('user_id', Auth::id())->paginate(10);
        return view('widgets.index');
    }

    public function indexDataTable() {
//        $widgets = DB::table('widgets')->where('user_id', Auth::id());
        $widgets = Widget::where('user_id', Auth::id());
        return Datatables::of($widgets)->addColumn('edit', function($widget) {
                    return view('comum.button_edit', [
                        'id' => $widget->id,
                        'route' => 'widgets.edit'
                    ]);
                })->addColumn('show', function($widget) {
                    return view('comum.button_show', [
                        'id' => $widget->id,
                        'route' => 'widgets.show'
                    ]);
                })->addColumn('delete', function($widget) {
                    return view('comum.button_delete', [
                        'id' => $widget->id,
                        'route' => 'widgets.destroy'
                    ]);
                })->editColumn('type', function($widget) {
                    $types = [
                        '1' => '----------------------',
                        '2' => 'Barra Lateral Direita',
                        '3' => 'Barra Lateral Esquerda',
                        '4' => 'Central'
                    ];
                    return $types[$widget->type];
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
        //$campaingns = Campaingn::where('user_id', Auth::id())->get();
        return view('widgets.create'); //->with(['campaingns' => $campaingns]);
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
            DB::beginTransaction();
            try {
                $post['user_id'] = Auth::id();
                $post['hashid'] = Hash::make(Auth::id() . "hash" . Carbon::now()->toDateTimeString());
                $widget = Widget::create($post);
//                $widget->campaingns()->sync($post['campaingns']);
                DB::commit();
                //$this->create_widget($widget->id);
                return redirect('widgets')
                                ->with('success', 'Widget cadastrado com sucesso.');
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $widget = Widget::where(['id' => $id, 'user_id' => Auth::id()])->first();
        if ($widget == null) {
            return back()->with('error'
                            , 'Widget não registrado no sistema.');
        } else if ($widget->user_id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode exibir os dados deste Widget.');
        } else {
            $jsonFile = Storage::disk(self::DISK)->get("data/widget.json");
            $json = json_decode($jsonFile);
            $json->js = str_replace(
                    ['[url]', '[version]'], [addslashes(url('/')), md5(time())], $json->js
            );
            $json->html = str_replace(
                    '[widget_hashid]', addslashes($widget->hashid), $json->html);
            $code = $json->js . "\n" . $json->html;

            return view('widgets.show', compact('widget'))
                            ->with('code', $code);
        }
    }

    public function logsDataTable($id) {
        $logs = CreativeLog::with(['creative'])->where('widget_id', $id);
        return Datatables::of($logs)->editColumn('image', function($log) {
                    return view('comum.image', [
                        'image' => $log->creative->image
                    ]);
                })->editColumn('revenue', function($log) {
                    return 'R$ ' . round($log->revenue, 2, PHP_ROUND_HALF_UP);
                })->rawColumns(
                        ['image']
                )->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        $widget = Widget::find($id);
        if ($widget == null) {
            return back()->with('error'
                            , 'Widget não registrado no sistema.');
        } else if ($widget->user_id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode exibir os dados deste Widget.');
        } else {
            $campaingns = Campaingn::all()->where('user_id', Auth::id());
            return view('widgets.update', compact('widget'))
                            ->with('campaingns', $campaingns);
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
            $widget = Widget::find($id);
            if ($widget == null) {
                return back()->with('error'
                                , 'Widget não registrado no sistema.');
            } else if ($widget->user_id != Auth::id()) {
                return back()->with('error'
                                , 'Não pode exibir os dados deste Widget.');
            } else {
                DB::beginTransaction();
                try {
                    $widget->update($post);
//                    $widget->campaingns()->sync($post['campaingns']);
                    DB::commit();
                    //$this->create_widget($widget->id);
                    return redirect('widgets')
                                    ->with('success', 'Widget editado com sucesso.');
                } catch (Exception $e) {
                    DB::rollBack();
                }
                return redirect('widgets');
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
        $widget = Widget::find($id);
        if ($widget == null) {
            return back()->with('error'
                            , 'Widget não registrado no sistema.');
        } else if ($widget->user_id != Auth::id()) {
            return back()->with('error'
                            , 'Não pode excluir este Widget.');
        } else {
            $widget->delete();
            return redirect('widgets')
                            ->with('success', 'Widget excluído com sucesso.');
        }
    }

    private function validar($post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            'name.min' => 'Nome muito curto.',
            'quantity.in' => 'Quantidade inválida.',
            'url.regex' => 'URL inválido.',
            'url.unique' => 'Já existe um Wdidget com esta URL.',
            'campaingns.required' => 'Selecione ao menos uma Campaingn.',
            'type.in' => 'Tipo inválido.',
            'type_layout.in' => "Layout inválido."
        );
        $rules = array(
            'name' => 'required|min:4',
            'quantity' => 'in:3,4,5,6',
            'url' => "regex:/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$/|unique:widgets",
            'type' => 'in:1,2,3,4',
            'type_layout' => 'in:1,2,3',
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }

    public function create_widget($id) {
        $url = "data/" . Auth::id() . "/{$id}";
        $widget = Widget::find($id);

        if (!Storage::disk(self::DISK)->exists($url)) {
            Storage::disk(self::DISK)->makeDirectory($url);
        }

        //$folder_name = Storage::disk('native_storage')->url("data/{$id}");
        // $folder_name = "data/{$id}";
        $file_name = $widget->name . '.json';

        $json_content = array(
            'name' => $widget->name,
            'url' => $widget->url,
            'type' => $widget->type
        );

        $this->create_json($url, $file_name, $json_content, $id);
    }

    public function create_json($folder_name, $file_name, $json_content, $widget_id) {
        //$abrir = fopen($folder_name."/".$file_name, "w");
        $abrir = $folder_name . "/" . $file_name;
        $widget_base = Storage::disk(self::DISK)->path('data/widget_example.js');
        $tpl = new Template($widget_base);

        $widget = Widget::find($widget_id);
        $tpl->WIDGET_HASHID = $widget->hashid;
        $tpl->WIDGET_TYPE = $widget->type;
        $tpl->WIDGET_ID = $widget->id;
        $tpl->block("BLOCK_CONTEUDO", true);

//        $creatives = Creative::all()->where('user_id', Auth::id());
//
//        $contador = 0;
//
//        foreach ($creatives as $creative) {
//            $tpl->TITLE = $creative->name;
//            $tpl->IMAGE = $creative->image;
//            $tpl->URL = $creative->url;
//            $tpl->CONTADOR = $contador;
//            $tpl->block("BLOCK_CONTEUDO", true);
//
//            $contador++;
//        }
//
//        $tpl->HASHID = sha1($widget_id);
//        $tpl->block("BLOCK_AJAX", true);

        $json_content['js'] = $tpl->parse();

        //fwrite($abrir,json_encode($json_content));
        //fclose($abrir);

        Storage::disk(self::DISK)->put($abrir, json_encode($json_content, JSON_PRETTY_PRINT));
    }

    public function create_widgets() {
        $widgets = Widget::all()->where('user_id', Auth::id());

        foreach ($widgets as $widget) {
            $this->create_widget($widget->id);
        }
    }

    public function widgetsStatistics() {
        return null;
    }

}
