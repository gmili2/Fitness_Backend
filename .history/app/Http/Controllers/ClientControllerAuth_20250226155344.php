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

    public function updatePassword(Request $request)
    {
        try {
            // Valider les données
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            // Récupérer le client actuellement authentifié
            $client = Auth::guard('client-api')->user();
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Gérer les autres exceptions
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
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
            'code_barre' => 'required|string',
        ]);

        $codeBarreRecu = $request->input('code_barre');
        $client = Auth::guard('client-api')->user();

        if (!$client) {
            return response()->json(['error' => 'Client non authentifié.'], 401);
        }
        // Vérification de la date d'expiration
        if (!$client->expiration_date || now()->gt($client->expiration_date)) {
            return response()->json(['error' => 'Votre abonnement a expiré.'], 403);
        }

        $userLieAuClient = $client->user;
        if (!$userLieAuClient) {
            return response()->json(['error' => 'Aucun utilisateur lié à ce client.'], 404);
        }

        $scanExistant = Scan::where('client_id', $client->id)
            ->where('barcode', $codeBarreRecu)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($scanExistant) {
            return response()->json(['error' => 'Vous avez déjà scanné ce code-barres aujourd\'hui.'], 400);
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
            return response()->json(['error' => 'Code-barres invalide.', 'isError' => true], 400);
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