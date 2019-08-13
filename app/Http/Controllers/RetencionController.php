<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Retencion;
use App\CausaRetencion;

class RetencionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Retencion::with('causas_retencion', 'guia_internacion', 'guia_internacion.persona', 'guia_internacion.persona.departamento_extension', 'guia_internacion.persona.carnet_productor', 'hoja_ruta', 'hoja_ruta.persona','hoja_ruta.persona.departamento_extension', 'localidad', 'municipio', 'departamento', 'marca', 'vehiculo', 'embarcacion', 'depositos')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Retencion::findOrFail($id);
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
        $data = Retencion::create($request->all());
        $data->causas_retencion()->attach($request->causas);
        return $data;
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
        $data = Retencion::findOrFail($id);
        $data->fill($request->all());
        $data->save();
        $data->causas_retencion()->detach();
        $data->causas_retencion()->attach($request->causas);
        return $data;
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) 
    {
        $data = Retencion::findOrFail($id);
        $data->delete();
        return $data;
    }
        
    /**
     * Lista recursos especificos por criterio
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Retencion::with('causas_retencion', 'persona', 'guia_internacion', 'hoja_ruta', 'persona.departamento_extension', 'departamento', 'municipio', 'localidad', 'vehiculo', 'marca', 'embarcacion')->where($request)->get();
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
        $data = Retencion::where($campo, Retencion::max($campo))->first();
        if ($data) {
            $correlativo = explode('/', $data->correlativo)[0] + 1;
        }
        return $correlativo.'/'.\Carbon::now()->year;
    }

    /**
     * Imprime un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id) {
        $headerHtml = view()->make('partial.head')->render();
        $pageMargins = [30, 25, 40, 30];
        $pageName = 'retencion.pdf';
        $data = [
            'retencion' => Retencion::with('guia_internacion', 'guia_internacion.persona', 'guia_internacion.persona.departamento_extension', 'guia_internacion.persona.carnet_productor', 'hoja_ruta', 'hoja_ruta.persona', 'localidad', 'municipio', 'departamento', 'marca', 'vehiculo', 'embarcacion')->find($id),
            'causas_retencion' => CausaRetencion::get()
        ];        
        return \PDF::loadView('retencion.print', $data)
        ->setOption('header-html', $headerHtml)
        ->setOption('page-size','Letter')
        ->setOption('margin-top', $pageMargins[0])
        ->setOption('margin-right', $pageMargins[1])
        ->setOption('margin-bottom', $pageMargins[2])
        ->setOption('margin-left', $pageMargins[3])
        ->setOption('encoding', 'utf-8')
        ->stream($pageName);
    }

    public function printHoja($id) {
        $headerHtml = view()->make('partial.head')->render();
        $pageMargins = [25, 20, 25, 30];
        $pageName = 'retencion.pdf';
        $data = [
            'retencion' => Retencion::with('causas_retencion','hoja_ruta', 'hoja_ruta.persona', 'hoja_ruta.persona.departamento_extension', 'hoja_ruta.persona.carnet_productor', 'localidad', 'municipio', 'departamento', 'marca', 'vehiculo', 'embarcacion')->find($id),
            'causas_retencion' => CausaRetencion::get()
        ];        
        return \PDF::loadView('retencion.printhoja', $data)
        ->setOption('header-html', $headerHtml)
        ->setOption('page-size','Letter')
        ->setOption('margin-top', $pageMargins[0])
        ->setOption('margin-right', $pageMargins[1])
        ->setOption('margin-bottom', $pageMargins[2])
        ->setOption('margin-left', $pageMargins[3])
        ->setOption('encoding', 'utf-8')
        ->stream($pageName);
    }
}
