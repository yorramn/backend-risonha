<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        return response([
            'user' => User::all()
        ],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);
        //create
        if ($request->password != $request->password_confirmation) {
            return response([
                'message' => 'Credenciais inválidas',
            ], 403);
        } else {
            $user = User::create([
                'name' => $attrs['name'],
                'email' => $attrs['email'],
                'password' => bcrypt($attrs['password'])
            ]);
            return response([
                'user' => $user,
                'token' => $user->createToken('secret')->plainTextToken
            ], 200);
        }
    }

    public function login(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        //login
        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Credenciais inválidas',
            ], 403);
        } else {
            return response([
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('secret')->plainTextToken
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
