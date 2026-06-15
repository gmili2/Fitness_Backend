@extends('layouts.appAdmin')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="page-header">
    <div>
        <h4>Tableau de bord</h4>
        <p class="text-muted mb-0" style="font-size:.875rem">Bienvenue, {{ Auth::guard('user_admin')->user()->name }}</p>
    </div>
</div>

@php
    $totalUsers   = \App\Models\User::count();
    $activeUsers  = \App\Models\User::where('is_active', true)->count();
    $totalClients = \App\Models\Client::count();
    $activeScans  = \App\Models\Scan::whereNull('date_pointage_sortie')->whereDate('created_at', today())->count();
@endphp

{{-- Stats --}}
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#fef3f2;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class='bx bx-building-house' style="font-size:1.4rem;color:#e53935"></i>
                </div>
                <div>
                    <p class="text-muted mb-0" style="font-size:.75rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px">Salles</p>
                    <h4 style="font-weight:700;margin:2px 0 0;font-size:1.5rem">{{ $totalUsers }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#ecfdf3;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class='bx bx-check-circle' style="font-size:1.4rem;color:#22c55e"></i>
                </div>
                <div>
                    <p class="text-muted mb-0" style="font-size:.75rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px">Salles actives</p>
                    <h4 style="font-weight:700;margin:2px 0 0;font-size:1.5rem">{{ $activeUsers }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#eff8ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class='bx bx-group' style="font-size:1.4rem;color:#2196f3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0" style="font-size:.75rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px">Clients</p>
                    <h4 style="font-weight:700;margin:2px 0 0;font-size:1.5rem">{{ $totalClients }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;background:#fff8e1;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class='bx bx-scan' style="font-size:1.4rem;color:#f59e0b"></i>
                </div>
                <div>
                    <p class="text-muted mb-0" style="font-size:.75rem;font-weight:500;text-transform:uppercase;letter-spacing:.5px">Scans aujourd'hui</p>
                    <h4 style="font-weight:700;margin:2px 0 0;font-size:1.5rem">{{ $activeScans }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dernières salles --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="mb-0" style="font-weight:600">Dernières salles ajoutées</h6>
        <a href="{{ route('admin.users') }}" class="text-danger text-decoration-none" style="font-size:.82rem">
            Voir toutes <i class='bx bx-right-arrow-alt'></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Salle</th>
                        <th>Email</th>
                        <th>Clients</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\User::latest()->take(5)->withCount('clients')->get() as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->image)
                                    <img src="{{ $user->image_url }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover" alt="">
                                @else
                                    <div style="width:32px;height:32px;border-radius:50%;background:#fef3f2;display:flex;align-items:center;justify-content:center">
                                        <i class='bx bxs-building' style="font-size:.9rem;color:#e53935"></i>
                                    </div>
                                @endif
                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none text-dark" style="font-weight:500;font-size:.875rem">
                                    {{ $user->name }}
                                </a>
                            </div>
                        </td>
                        <td class="text-muted" style="font-size:.82rem">{{ $user->email }}</td>
                        <td style="font-weight:600;font-size:.875rem">{{ $user->clients_count }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge-active">Actif</span>
                            @else
                                <span class="badge-inactive">Inactif</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:.82rem">{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
