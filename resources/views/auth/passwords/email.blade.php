@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                    <h4 class="mb-0">
                        <i class='bx bx-mail-send me-2'></i>{{ __('Réinitialisation du mot de passe') }}
                    </h4>
                </div>

                <div class="card-body" style="background-color: #f8f9fa;">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <i class='bx bx-check-circle me-2'></i>{{ session('status') }}
                        </div>
                    @endif

                    <div class="text-muted mb-4">
                        <i class='bx bx-info-circle me-2'></i>{{ __('Entrez votre adresse email et nous vous enverrons un lien de réinitialisation de mot de passe.') }}
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="email" class="form-label">
                                <i class='bx bx-envelope me-2'></i>{{ __('Adresse Email') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Entrez votre email">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class='bx bx-paper-plane me-2'></i>{{ __('Envoyer le lien de réinitialisation') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
