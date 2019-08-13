<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Pais;

class PaisController extends Controller
{
    /**
     * Display all resource
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Pais::get();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Pais::findOrFail($id);
    }

    /**
     * Store a newly Item if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Pais::create($request->all());
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
        $pais = Pais::findOrFail($id);
        $pais->fill($request->all());
        $pais->save();
        return $pais;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $pais = Pais::findOrFail($id);
        $pais->delete();
        return $pais;
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Pais::where($request)->get();
    }
}
