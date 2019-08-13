<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ruta;
use App\PuestoControl;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Ruta::with('puestos_de_control', 'municipio', 'origen', 'destino')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ruta = Ruta::create($request->all());
        $ruta->puestos_de_control()->attach($request->puestos_de_control);
        return $ruta;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Ruta::with('puestos_de_control', 'municipio')->findOrFail($id);
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
        $ruta = Ruta::findOrFail($id);
        $ruta->fill($request->all());
        $ruta->save();
        $ruta->puestos_de_control()->detach();
        $ruta->puestos_de_control()->attach($request->puestos_de_control);
        return $ruta;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->puestos_de_control()->detach();
        $ruta->delete();
        return $ruta;
    }

    public function codevalidate($code){
        $validate = Ruta::where('codigo', $code)->first();
        if($validate){
            return 'El codigo ya existe';
        }
        else{
            return '';
        }
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Ruta::with('puestos_de_control')->where($request)->get();
    }
}
