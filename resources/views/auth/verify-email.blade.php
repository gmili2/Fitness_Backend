@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                    <h4 class="mb-0">
                        <i class='bx bx-check-shield me-2'></i>{{ __('Vérification de l\'adresse email') }}
                    </h4>
                </div>

                <div class="card-body" style="background-color: #f8f9fa;">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            <i class='bx bx-check-circle me-2'></i>{{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </div>
                    @endif

                    <div class="text-muted mb-4">
                        <i class='bx bx-info-circle me-2'></i>
                        {{ __('Merci de votre inscription ! Avant de continuer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Si vous n\'avez pas reçu l\'email, nous pouvons vous en envoyer un autre.') }}
                    </div>

                    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class='bx bx-mail-send me-2'></i>{{ __('Renvoyer l\'email de vérification') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit" class="btn btn-link">
                            <i class='bx bx-log-out me-2'></i>{{ __('Se déconnecter') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
