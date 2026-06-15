@extends('layouts.appAdmin')
@section('page-title', $user->name)

@section('content')
<div class="page-header">
    <div>
        <h4>{{ $user->name }}</h4>
        <nav aria-label="breadcrumb" class="mt-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Salles</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary-custom">
            <i class='bx bx-edit'></i> Modifier
        </a>
        <a href="{{ route('admin.users') }}" class="btn btn-light border" style="border-radius:8px;font-size:.875rem;padding:9px 16px">
            <i class='bx bx-arrow-back me-1'></i> Retour
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- ── Fiche salle ── --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center pb-2">
                <div class="mb-3" style="position:relative;display:inline-block">
                    @if($user->image)
                        <img src="{{ $user->image_url }}" alt="" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #f2f4f7">
                    @else
                        <div style="width:90px;height:90px;border-radius:50%;background:#fef3f2;display:flex;align-items:center;justify-content:center;margin:0 auto;border:3px solid #f2f4f7">
                            <i class='bx bxs-building' style="font-size:2.5rem;color:#e53935"></i>
                        </div>
                    @endif
                    <span style="
                        position:absolute;bottom:2px;right:2px;
                        width:16px;height:16px;border-radius:50%;
                        background:{{ $user->is_active ? '#22c55e' : '#ef4444' }};
                        border:2px solid white;
                    "></span>
                </div>
                <h6 style="font-weight:700;margin-bottom:2px">{{ $user->name }}</h6>
                <p class="text-muted" style="font-size:.82rem;margin-bottom:12px">{{ $user->email }}</p>
                @if($user->is_active)
                    <span class="badge-active">Salle active</span>
                @else
                    <span class="badge-inactive">Salle inactive</span>
                @endif
            </div>
            <div class="card-body pt-0">
                <div style="background:#f9fafb;border-radius:10px;padding:16px">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted" style="font-size:.8rem">Inscription</span>
                        <span style="font-size:.82rem;font-weight:500">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted" style="font-size:.8rem">Dernière MAJ</span>
                        <span style="font-size:.82rem;font-weight:500">{{ $user->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted" style="font-size:.8rem">Clients</span>
                        <span style="font-size:.82rem;font-weight:700;color:#e53935">{{ $user->clients->count() }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    @if($user->is_active)
                        <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm w-100" style="border-radius:8px" onclick="return confirm('Désactiver cette salle ?')">
                                <i class='bx bx-pause me-1'></i> Désactiver
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100" style="border-radius:8px" onclick="return confirm('Activer cette salle ?')">
                                <i class='bx bx-play me-1'></i> Activer
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" style="border-radius:8px" onclick="return confirm('Supprimer cette salle définitivement ?')">
                            <i class='bx bx-trash me-1'></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Clients + Formulaire ── --}}
    <div class="col-lg-8 d-flex flex-column gap-4">
        {{-- Clients --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0" style="font-weight:600"><i class='bx bx-group me-2 text-danger'></i>Clients associés</h6>
                <span class="badge" style="background:#fef3f2;color:#e53935;padding:4px 10px;border-radius:20px;font-size:.75rem">
                    {{ $user->clients->count() }} clients
                </span>
            </div>
            <div class="card-body p-0">
                @if($user->clients->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->clients as $client)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:32px;height:32px;border-radius:50%;background:#fef3f2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                            <i class='bx bxs-user' style="color:#e53935;font-size:.9rem"></i>
                                        </div>
                                        <span style="font-weight:500;font-size:.875rem">{{ $client->first_name }} {{ $client->last_name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted" style="font-size:.82rem">{{ $client->email }}</td>
                                <td class="text-muted" style="font-size:.82rem">{{ $client->phone_number ?? '—' }}</td>
                                <td class="text-muted" style="font-size:.82rem">{{ $client->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn-icon btn-icon-edit" title="Modifier">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                        <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-del" title="Supprimer"
                                                onclick="return confirm('Supprimer ce client ?')">
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
                @else
                <div class="text-center py-5">
                    <i class='bx bx-user-x' style="font-size:3rem;color:#d0d5dd"></i>
                    <p class="text-muted mt-2 mb-0" style="font-size:.875rem">Aucun client associé</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Ajouter un client --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0" style="font-weight:600"><i class='bx bx-user-plus me-2 text-danger'></i>Ajouter un client</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.assign-clients.store', $user->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="new_client" value="1">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Âge</label>
                            <input type="number" name="age" class="form-control" value="{{ old('age') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date d'inscription</label>
                            <input type="date" name="registration_date" class="form-control" value="{{ old('registration_date') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date d'expiration</label>
                            <input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-lock-alt'></i></span>
                                <input type="password" name="password" id="client-password" class="form-control" required>
                                <button type="button" class="btn btn-light border-start-0 border"
                                    style="border-radius:0 8px 8px 0;border-left:none!important"
                                    onclick="togglePassword('client-password', this)">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn-primary-custom">
                            <i class='bx bx-user-plus'></i> Ajouter et associer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? "<i class='bx bx-show'></i>" : "<i class='bx bx-hide'></i>";
}
</script>
@endpush
