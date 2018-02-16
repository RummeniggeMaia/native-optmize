<?php

namespace App\Http\Controllers;

use App\Widget;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $widgets = Widget::with(['creativeLogs.creative.clicks.postback'])->get();
        return view('home', compact('widgets'));
    }

}
