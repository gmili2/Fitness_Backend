<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Scan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
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
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required',
            ]);

            $user = $this->user;

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        $clients = $this->user
            ->clients()
            ->with(['scans' => function ($query) {
                $query->whereNull('date_pointage_sortie');
            }])
            ->get();

        $clients->each(function ($client) {
            $lastScan = $client->scans->first();
            $client->activeInSalle = $lastScan && is_null($lastScan->date_pointage_sortie);
        });

        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'             => 'required|string|email|max:255',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'age'               => 'required|integer|min:0',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number'      => 'required|string|max:255',
            'registration_date' => 'required|date',
            'expiration_date'   => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $client                    = new Client();
            $client->email             = $request->email;
            $client->first_name        = $request->first_name;
            $client->last_name         = $request->last_name;
            $client->age               = $request->age;
            $client->phone_number      = $request->phone_number;
            $client->registration_date = $request->registration_date;
            $client->expiration_date   = $request->expiration_date;
            $client->user_id           = $this->user->id;
            $client->password          = Hash::make($request->phone_number);

            if ($request->hasFile('image')) {
                $image           = $request->file('image');
                $filename        = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('clients');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $filename);
                $client->image_path = 'clients/' . $filename;
            }

            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client created successfully',
                'data'    => $client,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating client',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $client = $this->user->clients()->find($id);

        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Client not found'], 404);
        }

        return response()->json($client);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'email'             => 'required|string|email|max:255',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'age'               => 'required|integer|min:0',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number'      => 'required|string|max:255',
            'registration_date' => 'required|date',
            'expiration_date'   => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $client                    = Client::findOrFail($id);
            $client->email             = $request->email;
            $client->first_name        = $request->first_name;
            $client->last_name         = $request->last_name;
            $client->age               = $request->age;
            $client->phone_number      = $request->phone_number;
            $client->registration_date = $request->registration_date;
            $client->expiration_date   = $request->expiration_date;

            if ($request->hasFile('image')) {
                if ($client->image_path) {
                    Storage::delete('public/' . $client->image_path);
                }
                $image           = $request->file('image');
                $filename        = time() . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('clients');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $image->move($destinationPath, $filename);
                $client->image_path = 'clients/' . $filename;
            }

            $client->save();

            return response()->json([
                'success' => true,
                'message' => 'Client updated successfully',
                'data'    => $client,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating client',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Client not found'], 404);
        }

        $client->delete();

        return response()->json(['success' => true, 'message' => 'Client deleted successfully']);
    }

    public function getActiveScans()
    {
        try {
            $activeScansCount = Scan::whereNull('date_pointage_sortie')
                ->whereDate('created_at', Carbon::today())
                ->count();

            return response()->json(['active_scans_count' => $activeScansCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function getScansCountByDay($date)
    {
        try {
            $startDate = Carbon::parse($date)->startOfWeek();
            $endDate   = Carbon::parse($date)->endOfWeek();

            $scanCounts = Scan::whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(fn($scan) => Carbon::parse($scan->created_at)->format('Y-m-d'))
                ->map(fn($day) => $day->count());

            return response()->json($scanCounts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }

    public function addScan(Request $request, $clieId)
    {
        try {
            $existingScan = Scan::where('client_id', $clieId)
                ->whereDate('created_at', Carbon::today())
                ->first();

            if ($existingScan) {
                return response()->json([
                    'success' => false,
                    'message' => 'A scan already exists for this client today.',
                ], 400);
            }

            $scan           = new Scan();
            $scan->client_id = $clieId;
            $scan->user_id   = $this->user->id;
            $scan->barcode   = $this->user->uuid;
            $scan->save();

            return response()->json([
                'success' => true,
                'message' => 'Scan added successfully',
                'data'    => $scan,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding scan',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
