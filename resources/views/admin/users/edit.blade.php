@extends('layouts.appAdmin')

@section('content')
<div class="container mt-5">
    <h2>Modifier l'Utilisateur</h2>
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="image">Photo de profil</label>
            @if($user->image)
                <div class="mb-2">
                    <img src="{{ $user->image_url }}" alt="Photo de profil" style="max-width: 100px;">
                </div>
            @endif
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-danger">Mettre à jour</button>
    </form>
</div>
@endsection