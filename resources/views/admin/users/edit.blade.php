@extends('layouts.appAdmin')
@section('page-title', 'Modifier la salle')

@section('content')
<div class="page-header">
    <div>
        <h4>Modifier la salle</h4>
        <nav aria-label="breadcrumb" class="mt-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Salles</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light border" style="border-radius:8px;font-size:.875rem;padding:9px 16px">
        <i class='bx bx-arrow-back me-1'></i> Retour
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0" style="font-weight:600"><i class='bx bx-edit me-2 text-danger'></i>Informations de la salle</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    {{-- Photo --}}
                    <div class="mb-4 text-center">
                        <div id="avatar-preview" style="
                            width:90px;height:90px;border-radius:50%;
                            background:#fef3f2;
                            display:flex;align-items:center;justify-content:center;
                            margin:0 auto 12px;
                            overflow:hidden;
                            border:3px solid #f2f4f7;
                            cursor:pointer;
                        " onclick="document.getElementById('image').click()">
                            @if($user->image)
                                <img src="{{ $user->image_url }}" style="width:100%;height:100%;object-fit:cover" alt="">
                            @else
                                <i class='bx bx-camera' style="font-size:2rem;color:#e53935"></i>
                            @endif
                        </div>
                        <label for="image" class="btn btn-light border" style="border-radius:8px;font-size:.8rem;cursor:pointer">
                            <i class='bx bx-upload me-1'></i> Changer la photo
                        </label>
                        <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewImage(this)">
                        <div class="text-muted mt-1" style="font-size:.75rem">JPG, PNG — max 2 Mo</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nom de la salle</label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-building-house'></i></span>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name') <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-envelope'></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            @error('email') <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Nouveau mot de passe <span class="text-muted fw-normal">(laisser vide pour ne pas changer)</span></label>
                            <div class="input-group">
                                <span class="input-icon"><i class='bx bx-lock-alt'></i></span>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="••••••••">
                                <button type="button" class="btn btn-light border-start-0 border"
                                    style="border-radius:0 8px 8px 0;border-left:none!important"
                                    onclick="togglePassword('password', this)">
                                    <i class='bx bx-show'></i>
                                </button>
                            </div>
                            @error('password') <div class="text-danger mt-1" style="font-size:.78rem">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-2 border-top">
                        <button type="submit" class="btn-primary-custom">
                            <i class='bx bx-save'></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light border" style="border-radius:8px;font-size:.875rem;padding:9px 16px">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('avatar-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.innerHTML = isText ? "<i class='bx bx-show'></i>" : "<i class='bx bx-hide'></i>";
}
</script>
@endpush
