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
                        <a href="{{ route('admin.users.assign-clients', $user->id) }}" class="btn btn-outline-danger">
                            <i class='bx bx-link me-2'></i>Gérer les clients associés
                        </a>
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
                                                    <a href="#" class="btn btn-sm btn-outline-dark">
                                                        <i class='bx bx-show'></i>
                                                    </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection