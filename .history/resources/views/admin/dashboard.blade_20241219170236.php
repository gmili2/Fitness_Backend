@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <!-- <div class="col-md-3" id="sidebar" style="background-color: #343a40; min-height: 100vh; color: white;"> -->
        <!-- <div class="p-3"> -->
        <!-- <h4 class="text-center text-white">Menu Administrateur</h4> -->
        <!-- <ul class="nav flex-column mt-4"> -->
        <!-- <li class="nav-item"> -->
        <!-- <a href="{{ route('admin.users') }}" class="nav-link text-white">Liste des utilisateurs</a> -->
        <!-- </li> -->
        <!-- <li class="nav-item"> -->
        <!-- <a href="{{ route('admin.users.create') }}" class="nav-link text-white">Ajouter un -->
        <!-- utilisateur</a> -->
        <!-- </li> -->
        <!-- </ul> -->
        <!-- </div> -->
        <!-- </div> -->

        <!-- Main Content -->
        <div class="col-md-9" id="main-content">
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h1 class="text-danger">Bienvenue au Tableau de Bord Administrateur</h1>
                <button class="btn btn-dark" id="toggle-sidebar">☰ Menu</button>
            </div>
            <p>Ceci est la page principale pour la gestion des administrateurs.</p>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Déconnexion</button>
            </form>
        </div>
    </div>
</div>
@endsection