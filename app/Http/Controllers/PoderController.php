<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poder;
use App\Persona;

class PoderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Poder::with('persona', 'poder')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $persona = Persona::create($request->persona);
        $poder = new Poder();
        $poder->persona_id = $request->persona_id;
        $poder->motivo = $request->motivo;
        $poder->adjuntos = $request->adjuntos;
        $poder->poder_id = $persona->id;
        $poder->user_id = $request->user_id;
        $poder->save();
        return Poder::with('persona', 'poder')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Persona::where('carnet_identidad', $id)->first();
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
        $poder = Poder::findOrFail($id);
        $poder->motivo = $request->motivo;
        if($request->adjuntos != ''){
            $poder->adjuntos = $request->adjuntos;
        }
        $poder->save();
        return $poder;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poder = Poder::findOrFail($id);
        $poder->poder()->delete();
        $poder->delete();
        return $poder;
    }
    
    public function show_fill($request) 
    {
        $request = json_decode($request, true);
        return Poder::where($request)->get();
    }
}
