<?php

namespace App\Http\Controllers;

use App\Widget;
use App\Campaingn;
use App\Creative;
use App\Http\Requests;
use App\CreativeLog;
use App\Click;
use App\Postback;
use App\WidgetLog;
use App\WidgetCustomization;
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
                })->addColumn('type_layout', function($widget) {
                    return array(
                            '0' => '-',
                            '1'=>'Native',
                            '2'=> 'Smart Link',
                            '3'=>'Banner Square (300x250)',
                            '4'=>'Banner Mobile (300x100)',
                            '5'=>'Banner Footer (928x244)',
                            '6'=>'Vídeo',
                        )[$widget->type_layout];
                })->rawColumns(
                        ['edit', 'show', 'delete']
                )->make(true);
    }

    public function widgetsDashboardTable() {
        $widgets = Widget::with('widgetLogs')
                        ->where(['user_id' => Auth::id()])->get();
        return Datatables::of($widgets)->editColumn('clicks', function($widget) {
                return $widget->widgetLogs->sum('clicks');
            })->editColumn('impressions', function($widget) {
                return $widget->widgetLogs->sum('impressions');
            })->editColumn('revenues', function($widget) {
                $revenues = $widget->widgetLogs->sum('revenues');
                return 'R$ ' . number_format($revenues, 4);
            })->make(true);
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
            $this->typeLayoutsProperties($request->has('type_layout'), $post);
            DB::beginTransaction();
            try {
                $post['user_id'] = Auth::id();
                $post['hashid'] = Hash::make(Auth::id() . "hash" . Carbon::now()->toDateTimeString());
                $widget = Widget::create($post);
                $post['widget_id'] = $widget->id;
                WidgetCustomization::create($post);
                DB::commit();
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
            $json = null;
            $version = '0005';//md5(time());
            $code = "";
            $iframe = "";
            if ($widget->type_layout == 1) {
                $jsonFile = Storage::disk(self::DISK)->get("data/widget.json");
                $json = json_decode($jsonFile);
                if ($json) {
                    $json->js = str_replace(
                        ['[url]', '[version]'], 
                        [addslashes(url('/')), $version], 
                        $json->js
                    );
                    $json->html = str_replace(
                        '[widget_hashid]', 
                        addslashes($widget->hashid), 
                        $json->html
                    );
                }
            } else if ($widget->type_layout == 2) {
                $jsonFile = Storage::disk(self::DISK)->get("data/smart_link.json");
                $json = json_decode($jsonFile);
                if ($json) {
                    $json->link = str_replace(
                        ['[url]', '[widget_hashid]', '[source]'], 
                        [
                            addslashes(url('/')),
                            $widget->hashid,
                            $widget->url
                        ], 
                        $json->link
                    );
                }
            } else if (in_array($widget->type_layout, [3,4,5])) {
                $jsonFile = Storage::disk(self::DISK)->get("data/banner.json");
                $jsonIFrameFile = Storage::disk(self::DISK)->get("data/iframe.json");
                $json = json_decode($jsonFile);
                $jsonIFrame = json_decode($jsonIFrameFile);
                if ($json && $jsonIFrame) {
                    $json->js = str_replace(
                        ['[url]', '[version]'], 
                        [addslashes(url('/')), $version], 
                        $json->js
                    );
                    $json->html = str_replace(
                        '[widget_hashid]', 
                        addslashes($widget->hashid), 
                        $json->html
                    );
                    $dims = $widget->getBannerDimensions();
                    $iframe = str_replace(
                        ['[url]', '[width]', '[height]'], 
                        [addslashes(url('/api/iframe?wg=' . $widget->hashid)), $dims[0], $dims[1]], 
                        $jsonIFrame->iframe
                    );
                }
            } else if($widget->type_layout == 6) {
                $jsonIFrameFile = Storage::disk(self::DISK)->get("data/iframe.json");
                $jsonIFrame = json_decode($jsonIFrameFile);
                if ($jsonIFrame) {
                    $dims = $widget->getBannerDimensions();
                    $iframe = str_replace(
                        [$jsonIFrame->iframe], 
                        [url('/api/preroll?wg=' . $widget->hashid)], 
                        $jsonIFrame->iframe
                    );
                }
            }
            if ($json) {
                $code = $json->link . "\n" 
                    .  $json->js . "\n" 
                    . $json->html;
            }
            return view('widgets.show', compact('widget'))
                            ->with([
                                'code'=>$code, 
                                'iframe' => $iframe,
                            ]);
        }
    }

    public function logsDataTable($id) {
        $logs = CreativeLog::with(['creative', 'campaingn'])->where('widget_id', $id);
        return Datatables::of($logs)->editColumn('image', function($log) {
                    return view('comum.image', [
                        'image' => $log->creative->image
                    ]);
                })->editColumn('revenue', function($log) {
                    return 'R$ ' . round($log->revenue, 2, PHP_ROUND_HALF_UP);
                })->editColumn('campaingn.name', function($log) {
                    return $log->campaingn == null ? '-' : $log->campaingn->name;
                })->editColumn('campaingn.type', function($log) {
                    return $log->campaingn == null ? '-' : $log->campaingn->type;
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
        $post['id'] =$id;
        $validacao = $this->validar($post);
        if ($validacao->fails()) {
            return redirect()->back()
                            ->withErrors($validacao)
                            ->withInput();
        } else {
            $this->typeLayoutsProperties($request->has('type_layout'), $post);
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
            'quantity.in' => 'Quantidade deve ser entre 1 e 6.',
            'url.regex' => 'URL inválido.',
            'url.unique' => 'Já existe um Wdidget com esta URL.',
            'campaingns.required' => 'Selecione ao menos uma Campaingn.',
            // 'type.in' => 'Tipo inválido.',
            'type_layout.in' => "Layout inválido.",
        );
        $rules = array(
            'name' => 'required|min:4',
            'quantity' => 'in:1,2,3,4,5,6',
            'url' => "regex:/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$/",
            //'url' => 'unique:widgets,url,' .$post['id'],
            // 'type' => 'in:1,2,3,4',
            'type_layout' => 'in:1,2,3,4,5,6',
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

        $file_name = $widget->name . '.json';

        $json_content = array(
            'name' => $widget->name,
            'url' => $widget->url,
            'type' => $widget->type
        );

        $this->create_json($url, $file_name, $json_content, $id);
    }

    public function create_json($folder_name, $file_name, $json_content, $widget_id) {
        $abrir = $folder_name . "/" . $file_name;
        $widget_base = Storage::disk(self::DISK)->path('data/widget_example.js');
        $tpl = new Template($widget_base);

        $widget = Widget::find($widget_id);
        $tpl->WIDGET_HASHID = $widget->hashid;
        $tpl->WIDGET_TYPE = $widget->type;
        $tpl->WIDGET_ID = $widget->id;
        $tpl->block("BLOCK_CONTEUDO", true);

        $json_content['js'] = $tpl->parse();

        Storage::disk(self::DISK)->put($abrir, json_encode($json_content, JSON_PRETTY_PRINT));
    }

    public function create_widgets() {
        $widgets = Widget::all()->where('user_id', Auth::id());

        foreach ($widgets as $widget) {
            $this->create_widget($widget->id);
        }
    }

    private function typeLayoutsProperties($hasLayout, &$post) {
        if ($hasLayout) {
            if (in_array($post['type_layout'], [3,4,5])) {
                $post['quantity'] = 1;
                $post['type'] = 1;
            } else if ($post['type_layout'] == 2) {
                $post['quantity'] = 0;
                $post['type'] = 1;
            }
        }
    }

    public function dailyLineChartData($id) {
        return DB::table('widget_logs')
                ->where('widget_id', $id)
                ->whereYear('widget_logs.created_at', Carbon::now()->year)
                ->whereMonth('widget_logs.created_at', Carbon::now()->month)
                ->groupBy('day')
                ->get([
                    DB::raw('SUM(widget_logs.impressions) as impressions'),
                    DB::raw('SUM(widget_logs.clicks) as clicks'),
                    DB::raw('SUM(widget_logs.revenues) as revenues'),
                    DB::raw('DAY(widget_logs.created_at) as day')]);
    }
}
