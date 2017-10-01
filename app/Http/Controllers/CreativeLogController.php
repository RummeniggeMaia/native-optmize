<?php

namespace App\Http\Controllers;

use App\CreativeLog;
use App\Http\Requests;
use Illuminate\Http\Request;

class CreativeLogController extends Controller
{
     /*
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $creative_logs = CreativeLog::all();
        return view('creative_logs.index',compact('creative_logs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('creative_logs.create');
    }

    /** Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $creative_log = $request->all();
        CreativeLog::create($creative_log);
        return redirect('api/creative_logs');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $creative_log = CreativeLog::find($id);
        return view('creative_logs.show',compact('creative_log')); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $creative_log = CreativeLog::find($id);
        return view('creative_logs.edit',compact('creative_log'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $creative_logUpdate = $request->all();
        $creative_log = CreativeLog::find($id);
        $creative_log->update($creative_logUpdate);
        return redirect('api/creative_logs');
    }

    /**
     * Remove the specified resource from the storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        CreativeLog::find($id)->delete();
        return redirect('api/creative_logs');
    }
}
