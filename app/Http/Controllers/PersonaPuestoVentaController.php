<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PersonaPuestoVenta;
use App\PuestoVenta;

class PersonaPuestoVentaController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return PersonaPuestoVenta::with('persona', 'puesto_venta', 'puesto_venta.municipio', 'autorizacion_cambio_puesto_venta')->get();
        
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return PersonaPuestoVenta::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return PersonaPuestoVenta::create($request->all());
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
        $data = PersonaPuestoVenta::findOrFail($id);
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
        $data = PersonaPuestoVenta::findOrFail($id);
        $puesto = PuestoVenta::findOrFail($data->puesto_venta->id);
        $puesto->delete();
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
        return PersonaPuestoVenta::where($request)->get();
    }
}
