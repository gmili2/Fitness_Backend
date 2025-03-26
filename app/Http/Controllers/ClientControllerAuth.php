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
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function updateDatePointageSortie(Request $request, $id)
    {
        $request->validate([
            'date_pointage_sortie' => 'required|date',
        ]);

        try {
            $scan = Scan::findOrFail($id);

            // Vérifier si la date de pointage de sortie est déjà remplie
            if ($scan->date_pointage_sortie) {
                return response()->json(['error' => 'Le pointage de sortie est déjà fait à la date suivante : ' . $scan->date_pointage_sortie], 400);
            }

            $scan->date_pointage_sortie = $request->input('date_pointage_sortie');
            $scan->save();

            return response()->json(['message' => 'Date de pointage de sortie mise à jour avec succès.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Scan non trouvé.'], 404);
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

        // Vérifier si le client a déjà un scan pour le jour actuel
        $today = now()->startOfDay();
        $existingScan = Scan::where('client_id', $client->id)
            ->where('created_at', '>=', $today)
            ->first();

        if ($existingScan) {
            return response()->json(['error' => 'Le client a déjà un scan pour aujourd\'hui.'], 400);
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
                'code_barre_recu' => $codeBarreRecu,
                "scan"=>$scan
            ], 200);
        } else {
            return response()->json(['error' => 'Code-barres invalide.'], 400);
        }
    }

    public function getClientScans($id)
    {
        try {
            $client = Client::with('scans')->findOrFail($id);
            return response()->json($client->scans);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
    public function me(Request $request)
    {
        $client = Auth::guard('client-api')->user(); // Récupère le client authentifié
        return response()->json($client);
    }

    public function testMethod($id)
    {
        dd($id);
        $client = Client::with('scans')->findOrFail($id);
        return response()->json($client->scans);
    }

    public function getScansWithinWeek($date)
    {
        try {
            //$client = Client::findOrFail($id);
            $client = Auth::guard('client-api')->user();
            $startDate = \Carbon\Carbon::parse($date)->startOfWeek();
            $endDate = \Carbon\Carbon::parse($date)->endOfWeek();

            $scans = Scan::where('client_id', $client->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
                });;

                $scanCounts = $scans->map(function ($day) {
                    return count($day);
                });
                return response()->json($scanCounts->toArray());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getActiveScans()
    {
        try {
            $activeScansCount = Scan::whereNull('date_pointage_sortie')->count();
            return response()->json(['active_scans_count' => $activeScansCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getScansCountByDay($date)
    {
        try {
            $client = Auth::guard('client-api')->user();
            $startDate = \Carbon\Carbon::parse($date)->startOfWeek();
            $endDate = \Carbon\Carbon::parse($date)->endOfWeek();

            $scans = Scan::where('client_id', $client->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
                });

            $scanCounts = $scans->map(function ($day) {
                return count($day);
            });

            return response()->json($scanCounts->toArray());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}