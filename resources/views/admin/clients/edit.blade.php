@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="text-danger mb-0">
                        <i class='bx bx-edit me-2'></i>Modifier le client
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="first_name" class="form-label">Prénom</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $client->first_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="last_name" class="form-label">Nom</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $client->last_name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $client->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Téléphone</label>
                            <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ $client->phone_number }}">
                        </div>

                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Date de naissance</label>
                            <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ $client->birth_date }}">
                        </div>

                        <div class="mb-3">
                            <label for="registration_date" class="form-label">Date d'inscription</label>
                            <input type="date" name="registration_date" id="registration_date" class="form-control" value="{{ $client->registration_date }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="expiration_date" class="form-label">Date d'expiration</label>
                            <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="{{ $client->expiration_date }}">
                        </div>

                        <div class="mb-3">
                            <label for="age" class="form-label">Âge</label>
                            <input type="number" name="age" id="age" class="form-control" value="{{ $client->age }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-danger">
                            <i class='bx bx-save me-2'></i>Enregistrer les modifications
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection