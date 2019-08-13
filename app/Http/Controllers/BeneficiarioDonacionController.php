<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BeneficiarioDonacion;

class BeneficiarioDonacionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return BeneficiarioDonacion::with('persona', 'persona.departamento_extension', 'persona.pais_nacimiento', 'persona.departamento_nacimiento', 'persona.provincia_nacimiento')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return BeneficiarioDonacion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return BeneficiarioDonacion::create($request->all());
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
        $data = BeneficiarioDonacion::findOrFail($id);
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
        $data = BeneficiarioDonacion::findOrFail($id);
        $data->delete();
        return $data;

    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $cadena
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return BeneficiarioDonacion::with('persona', 'persona.departamento_extension', 'persona.pais_nacimiento', 'persona.departamento', 'persona.provincia')->where($request)->get();
    }   
}
