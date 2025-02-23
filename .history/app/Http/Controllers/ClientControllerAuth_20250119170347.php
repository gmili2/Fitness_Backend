<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Scan;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientControllerAuth extends Controller
{
    public function updatePassword(Request $request, $id)
    {
        // Valider les données
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Trouver le client par ID
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        // Vérifier si le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $client->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Mettre à jour le mot de passe
        $client->password = Hash::make($request->new_password);
        $client->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if ($token = Auth::guard('client-api')->attempt($credentials)) { // Use the correct guard
            return response()->json(['access_token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function scannerCodeBarre(Request $request)
    {
        $request->validate([
            'code_barre' => 'required|string', // Validation du code-barres
        ]);

        $codeBarreRecu = $request->input('code_barre');

        $client = Auth::guard('client-api')->user();
        if (!$client) {
            return response()->json(['error' => 'Client non authentifié.'], 401);
        }

        $userLieAuClient = $client->user; // Récupère l'utilisateur lié au client

        if (!$userLieAuClient) {
            return response()->json(['error' => 'Aucun utilisateur lié à ce client.'], 404);
        }


        if ($codeBarreRecu === $userLieAuClient->uuid) {
            $scan = Scan::create([
                'client_id' => $client->id,
                'barcode' => $codeBarreRecu,
            ]);
            return response()->json([
                'message' => 'Code-barres validé.',
                'client' => $client,
                'user' => $userLieAuClient,
                'code_barre_recu' => $codeBarreRecu
            ], 200);
        } else {
            return response()->json(['error' => 'Code-barres invalide.'], 400);
        }
    }

    public function getClientScans($clientId)
    {
        $client = Client::with('scans')->findOrFail($clientId);
        return response()->json($client->scans);
    }
    public function me(Request $request)
    {
        $client = Auth::guard('client-api')->user(); // Récupère le client authentifié
        return response()->json($client);
    }
}