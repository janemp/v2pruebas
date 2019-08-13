<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MotivoIncineracion;

class MotivoIncineracionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return MotivoIncineracion::get();
        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return MotivoIncineracion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return MotivoIncineracion::create($request->all());
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
        $data = MotivoIncineracion::findOrFail($id);
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
        $data = MotivoIncineracion::findOrFail($id);
        $data->delete();
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
        return MotivoIncineracion::where($request)->get();
    }
}
