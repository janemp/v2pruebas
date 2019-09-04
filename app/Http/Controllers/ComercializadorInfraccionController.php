<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComercializadorInfraccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ComercialzadorInfraccion::with('comercializador_id','infraccion_id')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ComercializadorInfraccion::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ComercializadorInfraccion::findOrFail($id);
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
        $data=ComercializadorInfraccion::findOrFail($id);
        $data->fill($request->all());
        $data->save();
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=ComercializadorInfraccion::findOrFail($id);
        $data->delete();
        return $data;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return ComercializadorInfraccion::with('persona', 'persona.departamento_extension', 'persona.pais_nacimiento', 'persona.departamento', 'persona.provincia')->where($request)->get();
    }
}
