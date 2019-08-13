<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CarnetProductor;

class CarnetProductorController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return CarnetProductor::with('persona', 'persona.departamento_extension', 'departamento', 'provincia', 'municipio', 'federacion', 'central', 'regional', 'comunidad')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return CarnetProductor::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return CarnetProductor::create($request->all());
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
        $data = CarnetProductor::findOrFail($id);
        $data->fill($request->all());
        $data->save();
    }

    /**
     * Borra un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $data = CarnetProductor::findOrFail($id);
        $data->delete();
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
        return CarnetProductor::with('persona', 'persona.departamento_extension', 'departamento', 'provincia', 'municipio', 'federacion', 'central', 'regional', 'comunidad')->where($request)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Imprime un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id) {
        $pageSize = [70, 45];
        $pageMargins = [0, 0, 0, 0];
        $pageName = 'carnet_productor.pdf';
        $data = [
            'carnet_productor' => CarnetProductor::with('persona', 'persona.departamento_extension', 'departamento', 'provincia', 'municipio', 'federacion', 'central', 'regional', 'comunidad')
                                    ->find($id)
        ];        
        return \PDF::loadView('carnet_productor.print', $data)
        // ->setOption('page-size','Letter')
        ->setOption('page-width', $pageSize[0])
        ->setOption('page-height', $pageSize[1])
        ->setOption('margin-top', $pageMargins[0])
        ->setOption('margin-right', $pageMargins[1])
        ->setOption('margin-bottom', $pageMargins[2])
        ->setOption('margin-left', $pageMargins[3])
        ->setOption('encoding', 'utf-8')
        ->stream($pageName);
    }
}
