<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PuestoControl;

class PuestoControlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PuestoControl::with('municipio', 'rutas')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return PuestoControl::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $punto_de_control = PuestoControl::findOrFail($id);
        return $punto_de_control;
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
        $punto_de_control = PuestoControl::findOrFail($id);
        $punto_de_control->fill($request->all());
        $punto_de_control->save();
        return $punto_de_control;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $punto_de_control = PuestoControl::findOrFail($id);
        $punto_de_control->delete();
        return $punto_de_control;
    }

    public function codevalidate($code){
        $validate = PuestoControl::where('codigo', $code)->first();
        if($validate){
            return 'El codigo ya existe';
        }
        else{
            return '';
        }
    }
}
