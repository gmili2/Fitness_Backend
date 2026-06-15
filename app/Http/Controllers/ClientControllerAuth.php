<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Scan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientControllerAuth extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        if ($token = Auth::guard('client-api')->attempt($credentials)) {
            return response()->json(['access_token' => $token]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout()
    {
        Auth::guard('client-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(Request $request)
    {
        $client = Auth::guard('client-api')->user();

        if (!$client) {
            return response()->json(['error' => 'Client not authenticated'], 401);
        }

        $lastScan = Scan::where('client_id', $client->id)
            ->whereNull('date_pointage_sortie')
            ->latest('created_at')
            ->first();

        $client->scans = $lastScan ? [$lastScan] : [];

        return response()->json($client);
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required',
                'new_password'     => 'required',
            ]);

            $client = Auth::guard('client-api')->user();

            if (!Hash::check($request->current_password, $client->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }

            $client->password = Hash::make($request->new_password);
            $client->save();

            return response()->json(['message' => 'Password updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function scannerCodeBarre(Request $request)
    {
        $request->validate([
            'code_barre' => 'required|string',
        ]);

        $client = Auth::guard('client-api')->user();
        $salle  = $client->user;

        if (!$salle) {
            return response()->json(['error' => 'Aucune salle liée à ce client.'], 404);
        }

        $existingScan = Scan::where('client_id', $client->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->first();

        if ($existingScan) {
            return response()->json(['error' => "Le client a déjà un scan pour aujourd'hui."], 400);
        }

        $codeBarreRecu = $request->input('code_barre');

        if ($codeBarreRecu !== $salle->uuid) {
            return response()->json(['error' => 'Code-barres invalide.'], 400);
        }

        $scan = Scan::create([
            'client_id' => $client->id,
            'barcode'   => $codeBarreRecu,
        ]);

        return response()->json([
            'message'         => 'Code-barres validé.',
            'client'          => $client,
            'user'            => $salle,
            'code_barre_recu' => $codeBarreRecu,
            'scan'            => $scan,
        ]);
    }

    public function getClientScans($id)
    {
        try {
            $client = Client::with('scans')->findOrFail($id);
            return response()->json($client->scans);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateDatePointageSortie(Request $request, $id)
    {
        $request->validate([
            'date_pointage_sortie' => 'required|date',
        ]);

        try {
            $scan = Scan::findOrFail($id);

            if ($scan->date_pointage_sortie) {
                return response()->json([
                    'error' => 'Le pointage de sortie est déjà enregistré : ' . $scan->date_pointage_sortie,
                ], 400);
            }

            $scan->date_pointage_sortie = $request->input('date_pointage_sortie');
            $scan->save();

            return response()->json(['message' => 'Date de pointage de sortie mise à jour avec succès.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Scan non trouvé.'], 404);
        }
    }

    public function getScansWithinWeek($date)
    {
        try {
            $client = Auth::guard('client-api')->user();

            if (!$client) {
                return response()->json(['error' => 'Client not authenticated'], 401);
            }

            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate   = Carbon::parse($date)->endOfWeek();

            $timeByDay = Scan::where('client_id', $client->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(fn($scan) => Carbon::parse($scan->created_at)->format('Y-m-d'))
                ->map(function ($dayScans) {
                    $totalMinutes = $dayScans->sum(function ($scan) {
                        if (!$scan->date_pointage_sortie) {
                            return 0;
                        }
                        return Carbon::parse($scan->date_pointage_sortie)
                            ->diffInMinutes(Carbon::parse($scan->created_at));
                    });

                    $hours   = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;

                    return (float) $hours + ($minutes / 100);
                });

            return response()->json($timeByDay->toArray());
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getActiveScans()
    {
        try {
            $count = Scan::whereNull('date_pointage_sortie')->count();
            return response()->json(['active_scans_count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getScansCountByDay($date)
    {
        try {
            $client = Auth::guard('client-api')->user();

            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate   = Carbon::parse($date)->endOfWeek();

            $scanCounts = Scan::where('client_id', $client->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(fn($scan) => Carbon::parse($scan->created_at)->format('Y-m-d'))
                ->map(fn($day) => $day->count());

            return response()->json($scanCounts->toArray());
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}
