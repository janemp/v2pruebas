<?php

namespace App\Http\Controllers;

use App\Mercado;
use Illuminate\Http\Request;

class MercadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Mercado::with('departamento')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Mercado::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mercado = Mercado::findOrFail($id);
        return $mercado;
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
        $mercado = Mercado::findOrFail($id);
        $mercado->fill($request->all());
        $mercado->save();
        return $mercado;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mercado = Mercado::findOrFail($id);
        $mercado->delete();
        return $mercado;
    }

    public function codevalidate($code){
        $validate = Mercado::where('codigo', $code)->first();
        if($validate){
            return 'El codigo ya existe';
        }
        else{
            return '';
        }
    }
}
