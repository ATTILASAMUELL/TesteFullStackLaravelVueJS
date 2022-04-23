<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Register::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestValidated = $request->validate([
            'name' => 'required|min:5',
            'email'=> 'required|email|unique:registers',
            'password'=> 'required|min:9',
            'phone'=> 'required|numeric|min:5',
            'picture' => 'nullable|mimes:jpg,png'
        ]);

        $requestValidated['password'] = bcrypt($requestValidated['password']);

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $requestValidated['picture'] = $request->file('picture')->store('images');
        }

        return  Register::create($requestValidated);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Register $register)
    {
        return $register;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Register $register)
    {
        $requestValidated = $request->validate([
            'name' => 'required|min:5',
            //'email'=> 'required|email|unique:registers',
            'password'=> 'required|min:9',
            'phone'=> 'required|numeric|min:5',
            'picture' => 'nullable|mimes:jpg,png'
        ]);

        $requestValidated['password'] = bcrypt($requestValidated['password']);

        if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
            $requestValidated['picture'] = $request->file('picture')->store('images');
        }

        return  $register->update($requestValidated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Register $register)
    {
        $register->delete();
    }
}
