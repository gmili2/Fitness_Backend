<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientControllerAuth extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        dd("ok");
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if ($token = Auth::guard('client-api')->attempt($credentials)) { // Use the correct guard
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function scannerCodeBarre(Request $request)
    {
        $client = Auth::guard('client-api')->user(); // Récupère le client authentifié
        // Logique pour scanner le code barre en utilisant les infos du client
        return response()->json(['message' => 'Scanner Code Barre', 'client' => $client]);
    }

    public function me(Request $request)
    {
        $client = Auth::guard('client-api')->user(); // Récupère le client authentifié
        return response()->json(['client' => $client]);
    }
}