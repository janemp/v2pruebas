<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HojaRuta;
use App\ControlHojaRuta;
use App\ControlPuesto;
use App\PuestoControl;
use Illuminate\Support\Collection as Collection;

class HojaRutaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return HojaRuta::with('localidad', 'persona.departamento_extension', 'vehiculo', 'marca', 'procedencia', 'destino', 'ruta.puestos_de_control', 'retencion', 'puesto_venta')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $correlativo = 1;        
        $data = HojaRuta::where('correlativo', HojaRuta::max('correlativo'))->first();
        if ($data) {
            $correlativo = explode('/', $data->correlativo)[0] + 1;
            
        }
        $request['correlativo'] = $correlativo.'/'.\Carbon::now()->year;
        $request->control = true;
        $hoja_ruta = HojaRuta::create($request->all());
        return ControlHojaRuta::create([
            'user_id' => $request->user_id,
            'hoja_ruta_id' => $hoja_ruta->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return HojaRuta::with('localidad', 'persona.departamento_extension', 'vehiculo', 'marca', 'procedencia', 'destino', 'ruta.puestos_de_control', 'retencion', 'puesto_venta')->findOrFail($id);
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
        $hoja = HojaRuta::findOrFail($id);
        $hoja->fill($request->all());
        $hoja->save();
        return $hoja;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hoja = HojaRuta::findOrFail($id);
        $hoja->delete();
        return $hoja;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return HojaRuta::with('localidad', 'persona.departamento_extension', 'vehiculo', 'marca', 'procedencia', 'destino', 'ruta.puestos_de_control','retencion', 'puesto_venta')
            ->where($request)->orderBy('created_at', 'DESC')->get();
    }

    public function max($campo) 
    {
        $correlativo = 1;        
        $data = HojaRuta::where($campo, HojaRuta::max($campo))->first();
        if ($data) {
            $correlativo = explode('/', $data->correlativo)[0] + 1;
            
        }
        return $correlativo.'/'.\Carbon::now()->year;
    }

    public function print($id){
        $headerHtml = view()->make('partial.head')->render();
        $footerHtml = view()->make('hoja_ruta.foot')->render();
        $pageMargins = [30, 10, 40, 10];
        $pageName = 'hoja_ruta.pdf';
        $hoja_ruta = HojaRuta::with('localidad', 'persona.departamento_extension', 'vehiculo', 'marca', 'procedencia', 'destino', 'ruta.puestos_de_control', 'retencion', 'puesto_venta')->find($id);
        for($i = 0; $i < count($hoja_ruta->ruta->puestos_de_control); $i++){
            $temp = $hoja_ruta->ruta->puestos_de_control[$i];
            $hash = base64_encode($temp->nombre.$hoja_ruta->correlativo.$hoja_ruta->persona->carnet_identidad.$hoja_ruta->persona->nombre.$hoja_ruta->persona->primer_apellido);
            $hoja_ruta->ruta->puestos_de_control[$i]->hash = $hash;
        }
        $data = [
            'hoja_ruta' => $hoja_ruta
        ];      
        //dd($data);  
        return \PDF::loadView('hoja_ruta.print', $data)
        // ->setOption('header-html', $headerHtml)
        // ->setOption('footer-html', $footerHtml)
        // ->setOption('page-size', 'Legal')
        ->setOption('page-height', 345)
        ->setOption('page-width', 216)
        ->setOption('margin-top', $pageMargins[0])
        ->setOption('margin-right', $pageMargins[1])
        ->setOption('margin-bottom', $pageMargins[2])
        ->setOption('margin-left', $pageMargins[3])
        ->setOption('encoding', 'utf-8')
        ->stream($pageName);
    }
}
