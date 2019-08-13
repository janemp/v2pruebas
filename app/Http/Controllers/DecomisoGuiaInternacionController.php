<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DecomisoGuiaInternacion;

class DecomisoGuiaInternacionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return DecomisoGuiaInternacion::get();
        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return DecomisoGuiaInternacion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        DecomisoGuiaInternacion::create($request->all());
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
        $productor = DecomisoGuiaInternacion::findOrFail($id);
        $productor->fill($request->all());
        $productor->save();
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        $productor = DecomisoGuiaInternacion::findOrFail($id);
        $productor->delete();
    }
        
    /**
     * Lista recursos especificos por criterio
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function show_fill($request) 
    {
        $request = json_decode($request, true);
        return DecomisoGuiaInternacion::where($request)->get();
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function max() 
    {
        return DecomisoGuiaInternacion::max('id_retencion');
    }
}
