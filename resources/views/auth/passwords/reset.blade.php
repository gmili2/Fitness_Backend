@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                    <h4 class="mb-0">
                        <i class='bx bx-key me-2'></i>{{ __('Réinitialisation du mot de passe') }}
                    </h4>
                </div>

                <div class="card-body" style="background-color: #f8f9fa;">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">
                                <i class='bx bx-envelope me-2'></i>{{ __('Adresse Email') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Entrez votre email">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">
                                <i class='bx bx-lock-alt me-2'></i>{{ __('Nouveau mot de passe') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Entrez votre nouveau mot de passe">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="password-confirm" class="form-label">
                                <i class='bx bx-lock me-2'></i>{{ __('Confirmer le mot de passe') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class='bx bx-lock'></i></span>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmez votre nouveau mot de passe">
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class='bx bx-refresh me-2'></i>{{ __('Réinitialiser le mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
