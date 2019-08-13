<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZonaProductiva;

class ZonaProductivaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ZonaProductiva::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ZonaProductiva::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ZonaProductiva::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $zona = ZonaProductiva::findOrFail($id);
        $zona->fill($request->all());
        $zona->save();
        return $zona;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zona = ZonaProductiva::findOrFail($id);
        $zona->delete();
        return $zona;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return ZonaProductiva::where($request)->get();
    }
}
