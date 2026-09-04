<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        // Validação defensiva de credenciais criptografadas
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'As credenciais informadas não são válidas.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Bloqueio de Segurança para usuários inativos no banco de dados
        if (!$user->status) {
            return response()->json([
                'message'   => 'Essa conta de usuário está inativa, contacte seu administrador.'
            ], Response::HTTP_FORBIDDEN);
        }


        // Geração do Bearer Token atrelado ao nome do dispositivo do usuário
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'access_token'  =>  $token,
            'token_type'    => 'Bearer',
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'role'  => $user->role,
            ]
        ], Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoga/Deleta apenas o token que está sendo usado nessa sessão específica
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token removido e logout realizado com sucesso.'
        ], Response::HTTP_OK);
    }
}
