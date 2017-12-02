<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Campaingn;
use App\Creative;
use App\Http\Requests;
use App\CreativeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Providers\Template;
use Illuminate\Support\Facades\Storage;

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

    public function index() {
        $widgets = Widget::where('owner', Auth::id())->orderBy('name', 'asc')->paginate(5);
        return view('widgets.index', compact('widgets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $campaingns = Campaingn::all()->where('owner', Auth::id());
        return view('widgets.create')->with(['campaingns' => $campaingns]);
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
            DB::beginTransaction();
            try {
                $post['owner'] = Auth::id();
                $log = CreativeLog::create()->id;
                $post['creative_log'] = $log;
                $widget = Widget::create($post);
                $widget->campaingns()->sync($post['campaingns']);
                $widget->hashid = Hash::make($widget->id);
                $widget->save();
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
            return redirect('widgets');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id) {
        $widget = Widget::find($id);
        return view('widgets.show', compact('widget'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id) {
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
    public function update(Request $request, $id) {
        $post = $request->all();
        $v = $this->validar($post);
        if ($v->fails()) {
            return redirect()->back()
                            ->withErrors($v)
                            ->withInput();
        } else {
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
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        Widget::find($id)->delete();
        return redirect('widgets');
    }

    private function validar($post) {
        $mensagens = array(
            'name.required' => 'Insira um nome.',
            'name.min' => 'Nome muito curto.',
            'url.regex' => 'URL inválido.',
            'campaingns.required' => 'Selecione ao menos uma Campaingn.'
        );
        $rules = array(
            'name' => 'required|min:4',
            'url' => 'regex:/^((http[s]?):\/)?\/?([^:\/\s]+)((\/\w+)*\/)([\w\-\.]+[^#?\s]+)(.*)?(#[\w\-]+)?$/',
            'campaingns' => 'required|array|min:1'
        );
        $validator = Validator::make($post, $rules, $mensagens);
        return $validator;
    }
   
    public function create_widget($id)
    {
        $widget = Widget::find($id);
        
        if (!Storage::disk('teste')->exists("data/{$id}"))
        {
            Storage::disk('teste')->makeDirectory("data/{$id}");
        }

        $folder_name = Storage::disk('teste')->url("data/{$id}");
        $file_name = $widget->name . '.js';
        
        $json_content = array('name' => $widget->name, 'url' => $widget->url, 
                              'type' => $widgeg->type);

        $this->create_json($folder_name, $file_name, $json_content);
    }

    public function create_json($folder_name, $file_name, $json_content)
    {
        //$abrir = fopen($folder_name."/".$file_name, "w");
        $abrir = $folder_name . "/" . $file_name;
        $widget_base = Storage::disk('teste')->url('data/widget_example.js');
        $tpl = new Template($widget_base);

        $creatives = Creative::all()->where('owner', Auth::id());

        $contador = 0;

        foreach ($creatives as $creative) 
        {
            $tpl->TITLE = $creative->name;
            $tpl->IMAGE = $creative->image;
            $tpl->URL = $creative->url;
            $tpl->CONTADOR = $contador;
            $tpl->block("BLOCK_CONTEUDO", true);

            $contador++;
        }

        $json_content['js'] = $tpl->parse();

        //fwrite($abrir,json_encode($json_content));
        //fclose($abrir);

        Storage::disk('teste')->put($abrir, $json_content);
    }

    public function create_widgets()
    {
        $widgets = Widget::all()->where('owner', Auth::id());

        foreach ($widgets as $widget) 
        {
            $this->create_widget($widget->id);
        }
    }
}