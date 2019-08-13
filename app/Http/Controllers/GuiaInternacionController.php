<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GuiaInternacion;

class GuiaInternacionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return GuiaInternacion::with('persona', 'persona.departamento_extension', 'persona.carnet_productor', 'persona.carnet_productor.regional', 'ruta', 'ruta.puestos_de_control', 'vehiculo', 'marca', 'control_guia_internacion', 'retencion')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return GuiaInternacion::findOrFail($id);
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
        return GuiaInternacion::create($request->all());
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
        $data = GuiaInternacion::findOrFail($id);
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
        $data = GuiaInternacion::findOrFail($id);
        $data->delete();
        return $data;

    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $cadena
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return GuiaInternacion::with('persona', 'persona.departamento_extension', 'persona.carnet_productor', 
                'persona.carnet_productor.regional', 'ruta', 'ruta.puestos_de_control', 'vehiculo', 
                'marca', 'control_guia_internacion', 'retencion', 'retencion.causas_retencion',
                'retencion.departamento', 'retencion.municipio', 'retencion.localidad', 'retencion.vehiculo',
                'retencion.embarcacion')
            ->where($request)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    /**
     * Lista un recurso especifico por criterio
     *
     * @param  int  $campo
     * @return \Illuminate\Http\Response
     */
    public function max($campo) 
    {
        $correlativo = 1;        
        $data = GuiaInternacion::where($campo, GuiaInternacion::max($campo))->first();
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
        $footerHtml = view()->make('partial.foot')->render();
        $pageMargins = [25, 20, 25, 30];
        $pageName = 'guia_internacion.pdf';
        $data = [
            'guia_internacion' => GuiaInternacion::with('user', 'persona', 'persona.departamento_extension', 'persona.carnet_productor', 'persona.carnet_productor.regional', 'persona.carnet_productor.comunidad', 'ruta', 'ruta.puestos_de_control', 'vehiculo', 'marca', 'control_guia_internacion')->find($id),
        ];        
        return \PDF::loadView('guia_internacion.print', $data)
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml)
        ->setOption('page-size','Letter')
        ->setOption('margin-top', $pageMargins[0])
        ->setOption('margin-right', $pageMargins[1])
        ->setOption('margin-bottom', $pageMargins[2])
        ->setOption('margin-left', $pageMargins[3])
        ->setOption('encoding', 'utf-8')
        ->stream($pageName);
    }
}
