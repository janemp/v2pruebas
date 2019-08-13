<?php

namespace App\Http\Controllers;

use App\Central;
use Illuminate\Http\Request;

class CentralController extends Controller
{
    /**
     * Listado de recursos
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Central::with('municipio')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Central::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $central = Central::with('municipio')->findOrFail($id);
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
        $central = Central::findOrFail($id);
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
        $central = Central::findOrFail($id);
        $central->delete();
        return $central;
    }

    public function codevalidate($code){
        $validate = Central::where('codigo', $code)->first();
        if($validate){
            return 'El codigo ya existe';
        }
        else{
            return '';
        }
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Central::with('municipio')->where($request)->get();
    }
}
