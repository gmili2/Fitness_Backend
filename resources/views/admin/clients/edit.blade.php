@extends('layouts.appAdmin')
@section('page-title', 'Modifier le client')

@section('content')
<div class="page-header">
    <div>
        <h4>Modifier le client</h4>
        <nav aria-label="breadcrumb" class="mt-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Salles</a></li>
                @if($client->user_id)
                <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $client->user_id) }}" class="text-decoration-none text-muted">Salle</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $client->first_name }} {{ $client->last_name }}</li>
            </ol>
        </nav>
    </div>
    @if($client->user_id)
    <a href="{{ route('admin.users.show', $client->user_id) }}" class="btn btn-light border" style="border-radius:8px;font-size:.875rem;padding:9px 16px">
        <i class='bx bx-arrow-back me-1'></i> Retour
    </a>
    @endif
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0" style="font-weight:600"><i class='bx bx-user-pin me-2 text-danger'></i>Informations du client</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.clients.update', $client->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                value="{{ old('first_name', $client->first_name) }}" required>
                            @error('first_name') <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                value="{{ old('last_name', $client->last_name) }}" required>
                            @error('last_name') <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-envelope'></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $client->email) }}" required>
                            </div>
                            @error('email') <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-phone'></i></span>
                                <input type="text" name="phone_number" class="form-control"
                                    value="{{ old('phone_number', $client->phone_number) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Âge</label>
                            <input type="number" name="age" class="form-control" value="{{ old('age', $client->age) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $client->birth_date) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date d'inscription</label>
                            <input type="date" name="registration_date" class="form-control"
                                value="{{ old('registration_date', $client->registration_date) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date d'expiration</label>
                            <input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date', $client->expiration_date) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nouveau mot de passe <span class="text-muted fw-normal">(optionnel)</span></label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-lock-alt'></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••">
                                <button type="button" class="btn btn-light border-start-0 border"
                                    style="border-radius:0 8px 8px 0;border-left:none!important"
                                    onclick="togglePassword('password', this)">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-2 border-top">
                        <button type="submit" class="btn-primary-custom">
                            <i class='bx bx-save'></i> Enregistrer
                        </button>
                        @if($client->user_id)
                        <a href="{{ route('admin.users.show', $client->user_id) }}" class="btn btn-light border" style="border-radius:8px;font-size:.875rem;padding:9px 16px">
                            Annuler
                        </a>
                        @endif
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
