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
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function updatePassword(Request $request)
    {
        try {
            // Valider les données
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required',
            ]);
            // Récupérer le client actuellement authentifié
            $user = $this->user;
            if (!$user) {
                return response()->json(['message' => 'user not found'], 404);
            }
            // Vérifier si le mot de passe actuel est correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }
            // Mettre à jour le mot de passe
            $user->password = Hash::make($request->new_password);
            $user->save();
            return response()->json(['message' => 'Password updated successfully'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retourner les erreurs de validation
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Gérer les autres exceptions
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = $this->user
            ->clients()
            ->get();
        return $clients;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'expiration_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            $client = new Client();
            $client->email = $request->email;
            $client->first_name = $request->first_name;
            $client->last_name = $request->last_name;
            $client->age = $request->age;
            $client->phone_number = $request->phone_number;
            $client->registration_date = $request->registration_date;
            $client->expiration_date = $request->expiration_date;
            $client->user_id = $this->user->id;
            $client->password = Hash::make($request->phone_number);

            // Gérer l'upload de l'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('public/clients', $filename);
                $client->image_path = 'clients/' . $filename;
            }

            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'data' => $client
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating client',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->user->clients()->find($id);

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, client not found.'
            ], 400);
        }

        return $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'expiration_date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);
        }

        try {
            $client = Client::findOrFail($id);
            
            $client->email = $request->email;
            $client->first_name = $request->first_name;
            $client->last_name = $request->last_name;
            $client->age = $request->age;
            $client->phone_number = $request->phone_number;
            $client->registration_date = $request->registration_date;
            $client->expiration_date = $request->expiration_date;

            // Gérer l'upload de la nouvelle image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($client->image_path) {
                    Storage::delete('public/' . $client->image_path);
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('public/clients', $filename);
                $client->image_path = 'clients/' . $filename;
            }

            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'data' => $client
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating client',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $client = Client::find($id);
        // Vérifier si le client existe
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 404);
        }

        // Supprimer le client
        $client->delete();

        // Retourner une réponse de succès
        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully'
        ], 200);
    }

    public function login(Request $request)
    {
        @$validator = Validator::make($request->all(), [
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

    // public function scannerCodeBarre(Request $request)
    // {
    // $client = Auth::guard('client-api')->user(); // Récupère le client authentifié
    // Logique pour scanner le code barre en utilisant les infos du client
    // return response()->json(['message' => 'Scanner Code Barre', 'client' => $client]);
    // }

    public function me(Request $request)
    {
        $client = Auth::guard('client-api')->user(); // Récupère le client authentifié
        return response()->json(['client' => $client]);
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
            $client = $this->user;
            $startDate = \Carbon\Carbon::parse($date)->startOfWeek();
            $endDate = \Carbon\Carbon::parse($date)->endOfWeek();

            $scans = Scan::
            whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
                });

            $scanCounts = $scans->map(function ($day) {
                return count($day);
            });

            return response()->json($scanCounts);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Client not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }
}