<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Deposito;

class DepositoController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Deposito::with('retencion', 'retencion.guia_internacion', 'retencion.hoja_ruta', 'retencion.causas_retencion', 'retencion.persona', 'retencion.persona.departamento_extension', 'retencion.departamento', 'retencion.municipio', 'retencion.localidad', 'retencion.vehiculo', 'retencion.marca', 'retencion.embarcacion', 'devoluciones', 'devoluciones.resolucion_administrativa', 'decomisos', 'decomisos.resolucion_administrativa', 'devoluciones', 'decomisos', 'decomisos.donaciones')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Deposito::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $request['correlativo'] = $this->max('correlativo');
        return Deposito::create($request->all());
    }

    /**
     * Actualiza un recurso especifico
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) 
    {
        $data = Deposito::findOrFail($id);
        $data->fill($request->all());
        $data->save();
        return $data;
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $data = Deposito::findOrFail($id);
        $data->delete();
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Deposito::where($request)->get();
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function max($campo) 
    {
        $correlativo = 1;        
        $data = Deposito::where($campo, Deposito::max($campo))->first();
        if ($data) {
            $correlativo = explode('/', $data->correlativo)[0] + 1;
            if (\Carbon::now()->year > \Carbon::parse($data->created_at)->year) {
                $correlativo = 1;
            }
        }
        return $correlativo.'/'.\Carbon::now()->year;
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saldo() 
    {
        return '';
    }
}
