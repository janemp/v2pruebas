<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Persona;

class PersonaController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Persona::with('tipo_persona', 'departamento_extension', 'pais_nacimiento', 'departamento_nacimiento', 'provincia_nacimiento', 'persona', 'persona.departamento_extension', 'sustitucion', 'cesaciones', 'resoluciones_administrativas', 'puestos_venta')->get();        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Persona::with('tipo_persona', 'departamento_extension', 'pais_nacimiento', 'departamento_nacimiento', 'provincia_nacimiento', 'persona', 'persona.departamento_extension', 'sustitucion', 'cesaciones', 'resoluciones_administrativas')->findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Persona::create($request->all());
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
        $data = Persona::findOrFail($id);
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
    public function destroy($id) {
        $data = Persona::findOrFail($id);
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
        return Persona::with('poder', 'tipo_persona', 'departamento_extension', 'pais_nacimiento', 'departamento_nacimiento', 'provincia_nacimiento', 'persona', 'persona.departamento_extension', 'sustitucion', 'cesaciones', 'carnet_productor', 'carnet_comercializador', 'resoluciones_administrativas','resoluciones_administrativas.tipo_resolucion')
            ->where($request)->get();
    }

    public function hoja_ruta(){
        return Persona::with('poder', 'tipo_persona', 'departamento_extension', 'pais_nacimiento', 'departamento_nacimiento', 'provincia_nacimiento', 'persona', 'persona.departamento_extension', 'sustitucion', 'cesaciones', 'carnet_productor', 'carnet_comercializador', 'resoluciones_administrativas','resoluciones_administrativas.tipo_resolucion')
        ->whereBetween('tipo_persona_id', [2,6])->get();
    }
}
