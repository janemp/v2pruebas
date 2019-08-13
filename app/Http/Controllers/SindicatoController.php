<?php

namespace App\Http\Controllers;

use App\Sindicato;
use Illuminate\Http\Request;

class SindicatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sindicato::with('central')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Sindicato::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sindicato = Sindicato::findOrFail($id);
        return $sindicato;
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
        $sindicato = Sindicato::findOrFail($id);
        $sindicato->fill($request->all());
        $sindicato->save();
        return $sindicato;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sindicato = Sindicato::findOrFail($id);
        $sindicato->delete();
        return $sindicato;
    }

    public function codevalidate($code){
        $validate = Sindicato::where('codigo', $code)->first();
        if($validate){
            return 'El codigo ya existe';
        }
        else{
            return '';
        }
    }
}
