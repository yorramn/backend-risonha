<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        return response([
            'user' => User::all()
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            ], 200);
        } else {
            return response([
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('secret')->plainTextToken
            ], 200);
        }
    }
    public function logout()
    {
        if(auth()->user()->tokens()->delete()){
            return response()->json([
                'message' => 'Usuário deslogado com sucesso'
            ]);
        }else{
            return response()->json([
                'message' => 'Erro ao deslogar'
            ]);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if (isset($user)) {
            return Controller::retornarConteudo('Token: ' . $user->token, $user, 200);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function defineCargo(Request $request)
    {
        $user = User::where('name', $request->user_name)->get()->first();
        return $this->escolherCargo($user, $request->id_cargo);
    }

    private function escolherCargo($user, $idCargo)
    {
        $usuario = $user;
        $cargo = Role::findById($idCargo);


        if (!isset($usuario)) {
            return Controller::retornarConteudo('Usuário não encontrado!', null, 406);
        } else if (!isset($cargo)) {
            return Controller::retornarConteudo('Cargo não encontrado!', null, 406);
        } else {
            $usuario->assignRole($cargo);
            return Controller::retornarConteudo('Cargo de ' . $cargo->name . ' atribuido ao usuario ' . $usuario->name, $usuario, 200);
        }
    }
}
