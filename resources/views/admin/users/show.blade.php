@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="text-danger mb-0">
                        <i class='bx bxs-user-detail me-2'></i>Informations de l'utilisateur
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->image)
                            <img src="{{ $user->image_url }}" alt="Photo de profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <i class='bx bxs-user-circle text-danger' style="font-size: 5rem;"></i>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Nom</label>
                        <p class="h5">{{ $user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Email</label>
                        <p class="h5">{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Date d'inscription</label>
                        <p class="h5">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Dernière mise à jour</label>
                        <p class="h5">{{ $user->updated_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-dark">
                            <i class='bx bx-edit-alt me-2'></i>Modifier
                        </a>
                        <!-- <a href="{{ route('admin.users.assign-clients', $user->id) }}" class="btn btn-outline-danger">
                            <i class='bx bx-link me-2'></i>Gérer les clients associés
                        </a> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="text-danger mb-0">
                        <i class='bx bx-group me-2'></i>Clients associés
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->clients as $client)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bxs-user-circle fs-4 me-2 text-danger'></i>
                                                    {{ $client->first_name }} {{ $client->last_name }}
                                                </div>
                                            </td>
                                            <td>{{ $client->email }}</td>
                                            <td>{{ $client->phone_number }}</td>
                                            <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-sm btn-outline-dark">
                                                        <i class='bx bx-edit'></i> Modifier
                                                    </a>
                                                    <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?');">
                                                            <i class='bx bx-trash'></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class='bx bx-user-x text-danger' style="font-size: 4rem;"></i>
                            <p class="text-muted mt-2">Aucun client associé à cet utilisateur</p>
                        </div>
                    @endif

                    <!-- Formulaire pour créer un nouveau client et l'associer directement -->
                    <div class="mt-4">
                        <h5 class="text-danger mb-3">
                            <i class='bx bx-user-plus me-2'></i>Ajouter un nouveau client
                        </h5>
                        <form action="{{ route('admin.users.assign-clients.store', $user->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="new_client" value="1">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Prénom</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nom</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Téléphone</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Date de naissance</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="registration_date" class="form-label">Date d'inscription</label>
                                <input type="date" name="registration_date" id="registration_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="expiration_date" class="form-label">Date d'expiration</label>
                                <input type="date" name="expiration_date" id="expiration_date" class="form-control">
                            </div>
                            <!-- Ajout du champ âge dans le formulaire -->
                            <div class="mb-3">
                                <label for="age" class="form-label">Âge</label>
                                <input type="number" name="age" id="age" class="form-control">
                            </div>
                            <!-- Suppression de l'input de confirmation de mot de passe -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-danger">
                                <i class='bx bx-save me-2'></i>Ajouter et Associer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action*="assign-clients"]');
        const password = document.getElementById('password');

        form.addEventListener('submit', function (event) {
            if (!password.value) {
                event.preventDefault();
                alert('Le mot de passe est requis.');
            }
        });
    });
</script>
@endsection