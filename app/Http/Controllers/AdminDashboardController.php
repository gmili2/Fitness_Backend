<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    // Tableau de bord principal
    public function index()
    {
        return view('admin.dashboard');
    }

    // Lister les utilisateurs
    public function listUsers()
    {
        $users = User::all(); // Récupère tous les utilisateurs
        return view('admin.users.index', compact('users'));
    }

    // Formulaire de création d'utilisateur
    public function createUser()
    {
        return view('admin.users.create');
    }

    // Enregistrer un nouvel utilisateur
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'uuid' => Str::uuid()
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/users', $filename);
            $userData['image'] = 'users/' . $filename;
        }

        User::create($userData);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès');
    }

    // Formulaire de modification d'utilisateur
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Mettre à jour un utilisateur
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($user->image) {
                Storage::delete('public/' . $user->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/users', $filename);
            $user->image = 'users/' . $filename;
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès');
    }

    // Supprimer un utilisateur
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
    }

    // Afficher les détails d'un utilisateur
    public function showUser($id)
    {
        $user = User::with('clients')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // Afficher le formulaire d'association de clients
    public function showAssignClientForm($userId)
    {
        $user = User::findOrFail($userId);
        $clients = Client::whereNull('user_id')
                        ->orWhere('user_id', $userId)
                        ->get();
        return view('admin.users.assign-clients', compact('user', 'clients'));
    }

    // Associer des clients à un utilisateur
    public function assignClients(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        // Récupérer tous les clients actuellement associés
        $currentClientIds = $user->clients()->pluck('id')->toArray();
        
        // Les nouveaux IDs de clients sélectionnés
        $selectedClientIds = $request->input('client_ids', []);
        
        // Dissocier les clients qui ne sont plus sélectionnés
        Client::whereIn('id', array_diff($currentClientIds, $selectedClientIds))
              ->update(['user_id' => null]);
        
        // Associer les nouveaux clients sélectionnés
        Client::whereIn('id', $selectedClientIds)
              ->update(['user_id' => $userId]);

        return redirect()->route('admin.users.show', $userId)
                        ->with('success', 'Clients associés avec succès');
    }
}
