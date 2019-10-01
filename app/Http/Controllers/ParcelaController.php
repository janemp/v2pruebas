<?php

namespace App\Http\Controllers;

use App\Parcela;
use Illuminate\Http\Request;
use DB;
use App\AutorizacionRenovacion;
use App\Persona;

class ParcelaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Parcela::with('personas', 'comunidad', 'region','autorizacionrenovacion')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parcela = Parcela::create($request->all());
        $parcela->personas()->attach($request->personas);
        return $parcela;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Parcela::with('personas', 'comunidad', 'region','autorizacionrenovacion')->findOrFail($id);
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
        $parcela =  Parcela::findOrFail($id);
        $parcela->fill($request->all());
        $parcela->save();
        return $parcela;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parcela = Parcela::findOrFail($id);
        $parcela->delete();
        return $parcela;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Parcela::with('personas', 'comunidad', 'region','autorizacionrenovacion')->where($request)->get();
    }

    public function showfill($idPersona) 
    {
        return  $results = DB::select('select distinct parcelas.id,  parcelas.region_id, parcelas.codigo_catastral, parcelas.latitud, parcelas.longitud,
        parcelas.motivo_actualizacion_id, autorizacion_renovaciones.observaciones, autorizacion_renovaciones.informe_adjunto
        from parcelas 
        left join autorizacion_renovaciones on parcelas.autorizacion_id=autorizacion_renovaciones.id 
        inner join persona_parcela on parcelas.id = persona_parcela.parcela_id       
        where parcelas.deleted_at is null and persona_parcela.persona_id = ?', [$idPersona]);
    }

    public function showfillver($idPersona) 
    {
        return  $results = DB::select('select distinct parcelas.id,  parcelas.region_id, parcelas.codigo_catastral, parcelas.latitud, parcelas.longitud,
        parcelas.motivo_actualizacion_id, autorizacion_renovaciones.observaciones, autorizacion_renovaciones.informe_adjunto
        from parcelas 
        inner join autorizacion_renovaciones on parcelas.autorizacion_id=autorizacion_renovaciones.id 
        inner join persona_parcela on parcelas.id = persona_parcela.parcela_id       
        where parcelas.deleted_at is null and persona_parcela.persona_id = ?', [$idPersona]);
    }

    public function edit(Request $request, $id)
    {
        $parcela =  Parcela::findOrFail($id);
        $parcela->fill($request->all());
        $parcela->save();

        $parcela = Parcela::findOrFail($request->id); 
        $lastInsertId = AutorizacionRenovacion::orderBy('created_at', 'desc')->first();
        $parcela->autorizacion_id = $lastInsertId->id;
        $parcela->save();  
        return  $parcela;
    }   
}
