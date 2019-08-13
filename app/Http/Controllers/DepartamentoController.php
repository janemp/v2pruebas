<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Departamento;

class DepartamentoController extends Controller
{
    /**
     * Display all resource
     * @return json
     */
    public function index() 
    {
        return Departamento::get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Departamento::findOrFail($id);
    }

    /**
     * Store a newly Item if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Departamento::create($request->all());         
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
        $department = Departamento::findOrFail($id);
        $department->fill($request->all());
        $department->save();
        return $department;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $department = Departamento::findOrFail($id);
        $department->delete();
        return $department;
    }

    /**
     * Lista recursos especificos por criterio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Departamento::where($request)->get();
    }
}
