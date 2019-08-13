<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\User;

class UserController extends Controller
{
    /**
     * Update user
     *
     * @param  Request $request
     * @return App\User
     */
    public function index() 
    {
        return User::with('roles')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        return User::findOrFail($id);
    }

    /**
     * Store a newly Item if no one found in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_name' => $request->user_name,
            'last_name' => $request->last_name,
            'mothers_last_name' => $request->mothers_last_name,
            'password' => bcrypt($request->password),
        ]);
        $user->roles()->attach($request->roles);
        return $user;
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
        $user = User::findOrFail($id);
        $user->fill($request->all());
        $user->save();
        $user->roles()->detach();
        $user->roles()->attach($request->roles);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatepass(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();
        return $user;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }
}
