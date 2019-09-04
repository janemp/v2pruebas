<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZonaAutorizada;

class ZonaAutorizadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ZonaAutorizada::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ZonaAutorizada::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ZonaAutorizada::findOrFail($id);
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
        $zona = ZonaAutorizada::findOrFail($id);
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
        $zona = ZonaAutorizada::findOrFail($id);
        $zona->delete();
        return $zona;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return ZonaAutorizada::where($request)->get();
    }
}
