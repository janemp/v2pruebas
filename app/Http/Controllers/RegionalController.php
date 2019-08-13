<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Regional;

class RegionalController extends Controller
{
    /**
     * Display all resource
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        return Regional::get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return Regional::findOrFail($id);
    }

    /**
     * Store a newly Item if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        return Regional::create($request->all());
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
        $region = Regional::findOrFail($id);
        $region->fill($request->all());
        $region->save();
        return $region;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $region = Regional::findOrFail($id);
        $region->delete();
        return $region;
    }

    public function fill($request) 
    {
        $request = json_decode($request, true);
        return Regional::where($request)->get();
    }
}
