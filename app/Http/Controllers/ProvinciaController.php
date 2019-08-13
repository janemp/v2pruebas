<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Provincia;

class ProvinciaController extends Controller
{
    /**
     * Display all resource
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Provincia::with('departamento')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Provincia::findOrFail($id);
    }

    /**
     * Store a newly Item if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Provincia::create($request->all());         
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
        $provincia = Provincia::findOrFail($id);
        $provincia->fill($request->all());
        $provincia->save();
        return $provincia;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $provincia = Provincia::findOrFail($id);
        $provincia->delete();
        return $provincia;
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
        return Provincia::where($request)->get();
    }
}
