<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Autorizacion;

class AutorizacionController extends Controller
{
    /**
     * Lista los recursos
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Autorizacion::with('empresa')->get();
    }

    /**
     * Lista un recurso especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Autorizacion::findOrFail($id);
    }

    /**
     * Guarda un nuevo recurso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Autorizacion::create($request->all());
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
        $data = Autorizacion::findOrFail($id);
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
        $data = Autorizacion::findOrFail($id);
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
        return Autorizacion::with('empresa')->where($request)->get();
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
            'autorizacion' => Autorizacion::with('empresa')->find($id),
        ];        
        return \PDF::loadView('autorizacion.print', $data)
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
