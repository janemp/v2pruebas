<?php

namespace App\Http\Controllers;

use App\Federacion;
use Illuminate\Http\Request;

class FederacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Federacion::with('municipio')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Federacion::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $federacion = Federacion::findOrFail($id);
        return $federacion;
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
        $federacion = Federacion::findOrFail($id);
        $federacion->fill($request->all());
        $federacion->save();
        return $federacion;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $federacion = Federacion::findOrFail($id);
        $federacion->delete();
        return $federacion;
    }

    public function codevalidate($code){
        $validate = Federacion::where('codigo', $code)->first();
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
        return Federacion::where($request)->get();
    }
}
