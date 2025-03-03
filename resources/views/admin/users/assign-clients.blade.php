@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-danger">Associer des clients à {{ $user->name }}</h4>
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-dark">
                <i class='bx bx-arrow-back me-2'></i>Retour
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.assign-clients.store', $user->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="selectAll">
                                    </div>
                                </th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input type="checkbox" name="client_ids[]" 
                                                   value="{{ $client->id }}" 
                                                   class="form-check-input client-checkbox"
                                                   {{ $client->user_id == $user->id ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class='bx bxs-user-circle fs-4 me-2 text-danger'></i>
                                            {{ $client->first_name }} {{ $client->last_name }}
                                        </div>
                                    </td>
                                    <td>{{ $client->email }}</td>
                                    <td>{{ $client->phone_number }}</td>
                                    <td>
                                        @if($client->user_id == $user->id)
                                            <span class="badge bg-success">Associé</span>
                                        @elseif($client->user_id)
                                            <span class="badge bg-warning">Associé à un autre utilisateur</span>
                                        @else
                                            <span class="badge bg-secondary">Non associé</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-danger">
                        <i class='bx bx-link me-2'></i>Enregistrer les associations
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.client-checkbox').forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endpush
@endsection