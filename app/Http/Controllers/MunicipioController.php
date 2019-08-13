<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Municipio;

class MunicipioController extends Controller
{
    /**
     * Display all resource
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Municipio::with('provincia')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Municipio::findOrFail($id);
    }

    /**
     * Store a newly Item if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Municipio::create($request->all());         
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
        $municipio = Municipio::findOrFail($id);
        $municipio->fill($request->all());
        $municipio->save();
        return $municipio;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $municipio = Municipio::findOrFail($id);
        $municipio->delete();
        return $municipio;
    }

    /**
     * Lista recursos especificos por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Municipio::where($request)->get();
    }
}
