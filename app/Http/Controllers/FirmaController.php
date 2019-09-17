<?php

namespace App\Http\Controllers;

use App\Firma;
use Illuminate\Http\Request;

class FirmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Firma::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Firma::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Firma::findOrFail($id);
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
        $firma = Firma::findOrFail($id);
        $firma->fill($request->all());
        $firma->save();
        return $firma;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $firma = Firma::findOrFail($id);
        $firma->delete();
        return $firma;
    }
    public function fill()
    {
        $request = json_decode($request, true);
        return Firma::where($request)
            ->orderBy('created_at', 'DESC')
            ->get();
    }
}
