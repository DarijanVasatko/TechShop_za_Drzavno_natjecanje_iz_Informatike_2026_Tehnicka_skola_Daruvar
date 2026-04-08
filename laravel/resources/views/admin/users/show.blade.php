@extends('layouts.admin')

@section('title', 'Korisnik — ' . $user->full_name)

@section('content')

@php
    if (!function_exists('userOrderStatusBadgeClass')) {
        function userOrderStatusBadgeClass($status) {
            return match ($status) {
                'Na čekanju'    => 'bg-warning text-dark',
                'U obradi'      => 'bg-info text-dark',
                'Poslano'       => 'bg-primary',
                'Dostavljeno',
                'Dovršena'      => 'bg-success',
                'Otkazana'      => 'bg-danger',
                default         => 'bg-secondary',
            };
        }
    }
@endphp

<h2 class="fw-bold mb-4">
    <i class="bi bi-person me-2"></i> {{ $user->full_name }}
</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Osnovne informacije</div>
            <div class="card-body">
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Telefon:</strong> {{ $user->telefon ?? '—' }}</p>
                <p><strong>Registriran:</strong> {{ $user->created_at->format('d.m.Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">Upravljanje ulogama</div>
            <div class="card-body">
                <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_admin" id="isAdmin" {{ $user->is_admin ? 'checked' : '' }}>
                        <label class="form-check-label" for="isAdmin">Administrator</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_couirt" id="isCouirt" {{ $user->is_couirt ? 'checked' : '' }}>
                        <label class="form-check-label" for="isCouirt">Kurir</label>
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-save me-1"></i> Spremi promjene
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-3">Narudžbe korisnika</h4>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th class="text-end">Ukupno</th>
                    <th class="text-end">Akcija</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->narudzbe as $order)
                    <tr>
                        <td>#{{ $order->Narudzba_ID }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}</td>
                        <td>
                            <span class="badge {{ userOrderStatusBadgeClass($order->Status) }} px-3 py-2">
                                {{ $order->Status }}
                            </span>
                        </td>
                        <td class="text-end">
                            {{ number_format($order->Ukupni_iznos, 2) }} €
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detalji
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Korisnik još nema narudžbi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection