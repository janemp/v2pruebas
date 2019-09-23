<?php

namespace App\Http\Controllers;

use App\Sancion;
use App\Persona;
use App\ComercializadorInfraccion;
use Illuminate\Http\Request;
use DB;

class SancionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sancion::with('infraccion')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idpersona = Persona::findOrFail($request->persona_id);
        $sancion = Sancion::create($request->all());        
        $evento = new ComercializadorInfraccion;
        $evento->persona_id = $idpersona->id;
        $evento->sancion_id = $sancion->id;
        $evento->save();       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sancion = Sancion::findOrFail($id);
        return $sancion;
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
        $sancion = Sancion::findOrFail($id);
        $sancion->fill($request->all());
        $sancion->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {       
        $sancion = Sancion::findOrFail($id);  
        //$sancion->comercializador_infraccion()->detach();
        $sancion->delete();
    }

    public function codevalidate($code){
        $validate = Sancion::where('codigo', $code)->first();
        if($validate){
            return 'El codigo ya existe';
        }
        else{
            return '';
        }
    }

    public function fill($request) 
    { 
        // $request = json_decode($request, true);
        // return Sancion::with('infraccion.personas')->where($request)->get();

        // $request = json_decode($request, true);
        // return Sancion::where($request)->get();

        $request = json_decode($request, true);
        return Sancion::with('persona', 'persona.id', 'infraccion_id')
            ->where($request)->orderBy('id', 'DESC')->get();
    }

    public function showfill($idPersona) 
    {
        // //$request = json_decode($request, true);
        // return Sancion::join("infracciones","sanciones.infraccion_id","=","infracciones.id")
        // ->join("comercializador_infraccions","comercializador_infraccions.infraccion_id","=","infracciones.id")
        // ->join("personas","comercializador_infraccions.persona_id","=","personas.id")
        // ->where('id', $idPersona)->orderBy('id', 'DESC')->get();


        return  $results = DB::select('select distinct  sanciones.id, infracciones.nombre as nominfraccion,
        sanciones.infraccion_id, sanciones.codigo, sanciones.nombre, 
        sanciones.monto, sanciones.descripcion, sanciones.estado         
        from sanciones 
        inner join infracciones on sanciones.infraccion_id=infracciones.id 
        inner join comercializador_infraccions on sanciones.id=comercializador_infraccions.sancion_id 
        inner join personas on comercializador_infraccions.persona_id=personas.id
        where sanciones.deleted_at is null and personas.id = ?', [$idPersona]);
    }
}
