@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-danger">Liste des salles</h4>
            <a href="{{ route('admin.users.create') }}" class="btn btn-danger">
                <i class='bx bx-plus-circle me-2'></i>Ajouter une salle
            </a>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="mb-3 row g-2">
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Nom" value="{{ request('name') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ request('email') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-danger">Rechercher</button>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Date d'inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="avatar">
                                        @if($user->image)
                                            <img src="{{ $user->image_url }}" alt="Photo de profil" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <i class='bx bxs-user-circle text-danger' style="font-size: 2rem;"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="btn btn-sm btn-outline-dark">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="btn btn-sm btn-outline-dark">
                                            <i class='bx bx-edit-alt'></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="event.preventDefault();
                                                        if(confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                                                            document.getElementById('delete-form-{{ $user->id }}').submit();
                                                        }">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                        <form id="delete-form-{{ $user->id }}" 
                                              action="{{ route('admin.users.delete', $user->id) }}" 
                                              method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @if($user->is_active)
                                            <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">Désactiver</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Activer</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection