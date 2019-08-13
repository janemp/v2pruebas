<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Input;

use App\Comercializador;

class ComercializadorController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Comercializador::with('pais_nacimiento', 'departamento_extension', 'departamento_nacimiento', 'provincia_nacimiento','comercializador')->get();
        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Comercializador::with('pais_nacimiento', 'departamento_extension', 'departamento_nacimiento', 'provincia_nacimiento','comercializador', 'comercializador.departamento_extension')->findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Comercializador::create($request->all());
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
        $comercializador = Comercializador::findOrFail($id);
        $comercializador->fill($request->all());
        $comercializador->save();
        return $comercializador;
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $comercializador = Comercializador::findOrFail($id);
        $comercializador->delete();
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function fill($fill, $val) {
    //     return Comercializador::where($fill, $val)->get();
    // }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Comercializador::with('pais_nacimiento', 'departamento_extension', 'departamento_nacimiento', 'provincia_nacimiento','comercializador')
            ->where($request)->get();
    }
}
