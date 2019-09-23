<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Infraccion;

class InfraccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Infraccion::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $infraccion = Infraccion::create($request->all());
        // $infraccion->personas()->attach($request->comercializador_id);
        // return $infraccion;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $infraccion = Infraccion::findOrFail($id);
        return $infraccion;
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
        $infraccion = Infraccion::findOrFail($id);
        $infraccion->fill($request->all());
        $infraccion->save();
        return $infraccion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $infraccion = Infraccion::findOrFail($id);
        $infraccion->delete();
        return $infraccion;
    }

    public function codevalidate($code){
        $validate = Infraccion::where('codigo', $code)->first();
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
        // return Infraccion::with('comercializadorinfraccion', 'comercializadorinfraccion.infraccion_id', 'id')
        //     ->where($request)->orderBy('id', 'DESC')->get();  
        return Infraccion::with('comercializador_infraccions')->where($request)->get();

    }
   
}
