<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ControlHojaRuta;

class ControlHojaRutaController extends Controller
{
    /**
     * Devuelve los datos de control de hoja de ruta.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ControlHojaRuta::with('hoja_ruta', 'hoja_ruta.persona', 
                'hoja_ruta.persona.departamento_extension', 'hoja_ruta.vehiculo',
                'hoja_ruta.marca', 'hoja_ruta.procedencia', 'hoja_ruta.ruta.puestos_de_control', 
                'hoja_ruta.retencion', 'hoja_ruta.puesto_venta')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ControlHojaRuta::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ControlHojaRuta::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $control_hoja_ruta = ControlHojaRuta::findOrFail($id);
        $control_hoja_ruta->fill($request->all());
        $control_hoja_ruta->save();
        return $control_hoja_ruta;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $control_hoja_ruta = ControlHojaRuta::findOrFail($id);
        $control_hoja_ruta->delete();
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return ControlHojaRuta::with('hoja_ruta', 'hoja_ruta.persona', 
        'hoja_ruta.persona.departamento_extension', 'hoja_ruta.vehiculo',
        'hoja_ruta.marca', 'hoja_ruta.procedencia', 'hoja_ruta.ruta.puestos_de_control', 'hoja_ruta.puesto_venta'
        )->where($request)->get();
    }
}
