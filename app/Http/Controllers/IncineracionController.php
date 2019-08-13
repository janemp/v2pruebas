<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Incineracion;

class IncineracionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Incineracion::with('resolucion_administrativa', 'resolucion_administrativa.tipo_resolucion', 'resolucion_administrativa.persona', 'resolucion_administrativa.persona.departamento_extension', 'motivo_incineracion', 'responsables')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Incineracion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $data = Incineracion::create($request->all());
        foreach ($request->responsables as $res) {
            $data->responsables()->attach($res['id']);
            // return $res['id'];
        }
        return $data;
        // return $request->responsables[];
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
        $data = Incineracion::findOrFail($id);
        $data->fill($request->all());
        $data->save();
        $data->veedores()->detach();
        if($request->veedores){
            foreach($request->veedores as $veedor) {
                $data->veedores()->attach($veedor['id']);
            }
        }
        return $data;        
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $data = Incineracion::findOrFail($id);
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
        return Incineracion::where($request)->get();
    }

}
