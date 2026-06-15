@extends('layouts.appAdmin')
@section('page-title', 'Liste des salles')

@section('content')
<div class="page-header">
    <div>
        <h4>Salles</h4>
        <nav aria-label="breadcrumb" class="mt-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Accueil</a></li>
                <li class="breadcrumb-item active">Salles</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary-custom">
        <i class='bx bx-plus'></i> Ajouter une salle
    </a>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-600" style="font-weight:600">Toutes les salles</h6>
        <span class="badge" style="background:#f0f2f7;color:#667085;padding:5px 12px;border-radius:20px;font-size:.78rem">
            {{ $users->total() }} salles
        </span>
    </div>
    <div class="card-body p-0">
        <div class="px-4 py-3 border-bottom" style="background:#fafbfc">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <div class="search-bar">
                        <i class='bx bx-search'></i>
                        <input type="text" name="name" class="form-control" placeholder="Rechercher par nom…" value="{{ request('name') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="search-bar">
                        <i class='bx bx-envelope'></i>
                        <input type="text" name="email" class="form-control" placeholder="Rechercher par email…" value="{{ request('email') }}">
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom flex-grow-1 justify-content-center">
                        <i class='bx bx-search'></i> Filtrer
                    </button>
                    @if(request('name') || request('email'))
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-light border" style="border-radius:8px;padding:9px 14px">
                            <i class='bx bx-x'></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table" id="users-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Salle</th>
                        <th>Email</th>
                        <th>Clients</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td class="text-muted" style="font-size:.78rem">{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($user->image)
                                    <img src="{{ $user->image_url }}" alt="" class="user-avatar">
                                @else
                                    <div class="user-avatar-placeholder">
                                        <i class='bx bxs-user'></i>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:500">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            <span style="font-weight:600">{{ $user->clients_count ?? $user->clients->count() }}</span>
                            <span class="text-muted" style="font-size:.78rem"> clients</span>
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge-active"><i class='bx bxs-circle' style="font-size:.5rem;vertical-align:middle;margin-right:4px"></i>Actif</span>
                            @else
                                <span class="badge-inactive"><i class='bx bxs-circle' style="font-size:.5rem;vertical-align:middle;margin-right:4px"></i>Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn-icon btn-icon-view" title="Voir">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon btn-icon-edit" title="Modifier">
                                    <i class='bx bx-edit'></i>
                                </a>

                                @if($user->is_active)
                                    <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-icon" title="Désactiver"
                                            style="background:#fff8e1;color:#f59e0b"
                                            onclick="return confirm('Désactiver cette salle ?')">
                                            <i class='bx bx-pause'></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-icon" title="Activer"
                                            style="background:#ecfdf3;color:#027a48"
                                            onclick="return confirm('Activer cette salle ?')">
                                            <i class='bx bx-play'></i>
                                        </button>
                                    </form>
                                @endif

                                <form id="delete-{{ $user->id }}" action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-icon btn-icon-del" title="Supprimer"
                                        onclick="confirmDelete({{ $user->id }})">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
            <small class="text-muted">
                Affichage {{ $users->firstItem() }}–{{ $users->lastItem() }} sur {{ $users->total() }} résultats
            </small>
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette salle ? Cette action est irréversible.')) {
        document.getElementById('delete-' + id).submit();
    }
}
</script>
@endpush
