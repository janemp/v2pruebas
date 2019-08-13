<?php

namespace App\Http\Controllers;

use App\TipoResolucion;
use Illuminate\Http\Request;

class TipoResolucionController extends Controller
{
    /**
     * Listado de recursos
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TipoResolucion::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return TipoResolucion::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $central = TipoResolucion::with('municipio')->findOrFail($id);
        return $central;
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
        $central = TipoResolucion::findOrFail($id);
        $central->fill($request->all());
        return $central;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $central = TipoResolucion::findOrFail($id);
        $central->delete();
        return $central;
    }
}
