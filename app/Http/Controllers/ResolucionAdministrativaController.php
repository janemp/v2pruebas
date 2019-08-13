<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ResolucionAdministrativa;

class ResolucionAdministrativaController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return ResolucionAdministrativa::with('persona', 'persona.departamento_extension', 'tipo_resolucion')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return ResolucionAdministrativa::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return ResolucionAdministrativa::create($request->all());
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
        $data = ResolucionAdministrativa::findOrFail($id);
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
        $data = ResolucionAdministrativa::findOrFail($id);
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
        return ResolucionAdministrativa::with('persona', 'persona.departamento_extension', 'tipo_resolucion')
            ->where($request)->orderBy('id', 'DESC')->get();
    }

}
