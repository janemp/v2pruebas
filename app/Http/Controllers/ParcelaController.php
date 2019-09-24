<?php

namespace App\Http\Controllers;

use App\Parcela;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Parcela::with('personas', 'comunidad', 'region')->get();
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
        return Parcela::with('personas', 'comunidad', 'region')->findOrFail($id);
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
        return Parcela::with('personas', 'comunidad', 'region')->where($request)->get();
    }
}
