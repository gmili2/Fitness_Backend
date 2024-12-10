<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();

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
        //Validate data
        $data = $request->only('email', 'first_name', 'last_name', 'age', 'image_path', 'phone_number', 'registration_date', 'expiration_date', 'created_at', 'updated_at', 'user_id');
        $validator = Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'image_path' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'expiration_date' => 'required|date',
            'user_id' => 'nullable|integer|exists:users,id'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        //Request is valid, create new client
        try {
            $client = new Client();

            $client->email = $data['email'];
            $client->first_name = $data['first_name'];
            $client->last_name = $data['last_name'];
            $client->age = $data['age'];
            $client->image_path = $data['image_path'];
            $client->phone_number = $data['phone_number'];
            $client->registration_date = $data['registration_date'];
            $client->expiration_date = $data['expiration_date'];
            $client->user_id = $this->user->id;
            $client->save();
        } catch (\Exception $e) {
            dd($e);
        }

        //Client created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Client created successfully',
            'data' => $client
        ], Response::HTTP_OK);
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
        // Récupérer le client par son ID
        $client = Client::find($id);

        // Vérifier si le client existe
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Valider les données entrantes
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'email' => 'required|string|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'image_path' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'expiration_date' => 'required|date',
            'user_id' => 'nullable|integer|exists:users,id'
        ]);

        // Retourner une réponse en cas d'échec de validation
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages()
            ], Response::HTTP_BAD_REQUEST);
        }

        // Mettre à jour les données du client
        $client->update($request->only('name', 'sku', 'price', 'quantity', 'email', 'first_name', 'last_name', 'age', 'image_path', 'phone_number', 'registration_date', 'expiration_date', 'user_id'));

        // Retourner une réponse de succès
        return response()->json([
            'success' => true,
            'message' => 'Client updated successfully',
            'data' => $client
        ], Response::HTTP_OK);
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
}