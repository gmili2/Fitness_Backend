<div>
    <h1>Bienvenue au Tableau de Bord Administrateur</h1>
    <p>Ceci est la page principale pour la gestion des administrateurs.</p>
</div>
<form method="POST" action="{{ route('admin.logout') }}">
    @csrf
    <button type="submit">Déconnexion</button>
</form>