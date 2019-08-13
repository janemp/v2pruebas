<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ControlGuiaInternacion;

class ControlGuiaInternacionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return ControlGuiaInternacion::with('guia_internacion', 'guia_internacion.persona', 'guia_internacion.persona.departamento_extension', 'guia_internacion.persona.carnet_productor', 'guia_internacion.ruta', 'guia_internacion.ruta.puestos_de_control', 'guia_internacion.retencion')->get();
        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return ControlGuiaInternacion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return ControlGuiaInternacion::create($request->all());
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
        $data = ControlGuiaInternacion::findOrFail($id);
        $data->fill($request->all());
        $data->save();
        return $data;
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        $data = ControlGuiaInternacion::findOrFail($id);
        $data->delete();
        return $data;
    }
        
    /**
     * Lista recursos especificos por criterio
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return ControlGuiaInternacion::where($request)->get();
    }
}
