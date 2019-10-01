<?php

namespace App\Http\Controllers;

use App\AutorizacionRenovacion;
use Illuminate\Http\Request;
use DB;
use App\Parcela;

class AutorizacionRenovacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AutorizacionRenovacion::with('parcela')->get();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $autorizacion = AutorizacionRenovacion::create($request->all());
        // $autorizacion->parcelas()->attach($request->parcelas);
        // return $autorizacion;
        return AutorizacionRenovacion::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AutorizacionRenovacion  $autorizacionRenovacion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return AutorizacionRenovacion::findOrFail($id);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AutorizacionRenovacion  $autorizacionRenovacion
     * @return \Illuminate\Http\Response
     */
    public function edit(AutorizacionRenovacion $autorizacionRenovacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AutorizacionRenovacion $autorizacionRenovacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = AutorizacionRenovacion::findOrFail($id);
        $data->fill($request->all());
        $data->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AutorizacionRenovacion  $autorizacionRenovacion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = AutorizacionRenovacion::findOrFail($id);
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  string
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return AutorizacionRenovacion::with('parcela')->where($request)->get();
    }

    public function showfill($idParcela) 
    {
        return  $results = DB::select('select distinct autorizacion_renovaciones.id,comunidades.nombre, parcelas.hectareas, parcelas.latitud, parcelas.longitud,
        parcelas.descripcion, autorizacion_renovaciones.verificacion_destruccion
        from parcelas 
        inner join autorizacion_renovaciones on parcelas.autorizacion_id=autorizacion_renovaciones.id    
        inner join comunidades on parcelas.comunidad_id = comunidades.id
        where parcelas.deleted_at is null and parcelas.id = ?', [$idParcela]);
    }
}
