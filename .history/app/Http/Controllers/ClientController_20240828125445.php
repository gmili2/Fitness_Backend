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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('ok');
        //
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
try{
    $client = Client::create([
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'age' => $data['age'],
            'image_path' => $data['image_path'],
            'phone_number' => $data['phone_number'],
            'registration_date' => $data['registration_date'],
            'expiration_date' => $data['expiration_date'],
            'user_id' => $this->user->id,  // Spécification explicite de l'ID utilisateur
            'created_at' => now(),
            'updated_at' => now(),r
        ]);
}
catch(\Exception $e){
    dd($e);
}
       
       
       
       
       
       
       
       
       
       
       
       
       
dd($client);
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //Validate data
        $data = $request->only('name', 'sku', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update client
        $client = $client->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        //Client updated, return success response
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
    public function destroy(Client $client)
    {
        $client->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully'
        ], Response::HTTP_OK);
    }
}