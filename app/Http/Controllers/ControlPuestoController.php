<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ControlPuesto;

class ControlPuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ControlPuesto::with('control_hoja_ruta', 'puesto_control')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ControlPuesto::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ControlPuesto::findOrFail($id);
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
        $control_puesto = ControlPuesto::findOrFail($id);
        $control_puesto->fill($request->all());
        $control_puesto->save();
        return $control_puesto;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $control_puesto = ControlPuesto::findOrFail($id);
        $control_puesto->delete();
        return $control_puesto;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return ControlPuesto::where($request)->get();
    }
}
