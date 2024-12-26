@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="background-color: #dc3545; color: white;">
                    <h4>Inscription Administrateur</h4>
                </div>
                <div class="card-body" style="background-color: #f8f9fa;">
                    <form method="POST" action="{{ route('admin.register.submit') }}">
                        @csrf
                        <!-- Nom -->
                        <div class="form-group">
                            <label for="name">Nom</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Entrez votre nom" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Entrez votre email" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Entrez un mot de passe" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Confirmation du mot de passe -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirmer le mot de passe</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Confirmez votre mot de passe" required>
                        </div>

                        <!-- Bouton S'inscrire -->
                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-danger btn-block">S'inscrire</button>
                        </div>
                    </form>

                    <!-- Lien vers la page de connexion -->
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.login') }}" class="btn btn-dark btn-block">Retour à la Connexion</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection