<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sustitucion;

class SustitucionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Sustitucion::get();
        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Sustitucion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Sustitucion::create($request->all());
    }

    /**
     * Actualiza un recurso especifico
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) 
    {
        $data = Sustitucion::findOrFail($id);
        $data->fill($request->all());
        $data->save();
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $data = Sustitucion::findOrFail($id);
        $data->delete();
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  string
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Sustitucion::where($request)->get();
    }
}
