@php
    function statusBadgeClass($status) {
        return match ($status) {
            'Na čekanju'    => 'bg-warning text-dark',
            'U obradi'      => 'bg-info text-dark',
            'Poslano'       => 'bg-primary',
            'Dostavljeno'   => 'bg-success',
            'Dovršena'      => 'bg-success',
            'Otkazana'      => 'bg-danger',
            default         => 'bg-secondary',
        };
    }
@endphp

@extends('layouts.admin')
@section('content')
<h4 class="mb-3">Narudžbe</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Kupac</th>
                <th>Ukupno</th>
                <th>Status</th>
                <th>Datum</th>
                <th class="text-end">Detalji</th>
            </tr>
            </thead>
            <tbody>
@forelse($orders as $order)
    <tr>
        <td>#{{ $order->Narudzba_ID }}</td>

        <td>
            {{ $order->user->full_name ?? $order->user->email ?? '-' }}
        </td>

        <td>{{ number_format($order->Ukupni_iznos, 2) }} €</td>

        <td>
            <span class="badge {{ statusBadgeClass($order->Status) }} px-3 py-2">
                {{ $order->Status }}
            </span>
        </td>

        <td>{{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}</td>

        <td class="text-end">

            <a href="{{ route('admin.orders.show', $order) }}"
               class="btn btn-sm btn-outline-secondary me-1">
                <i class="bi bi-pencil-square"></i> Uredi
            </a>

            @if($order->Status !== 'Otkazana')
                <form action="{{ route('admin.orders.cancel', $order) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Jeste li sigurni da želite otkazati narudžbu?');">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </form>
            @endif

            @if(!in_array($order->Status, ['Dostavljeno', 'Dovršena', 'Otkazana']))
                <form action="{{ route('admin.orders.close', $order) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-outline-success">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </form>
            @endif

        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-muted py-4">
            Nema narudžbi.
        </td>
    </tr>
@endforelse
</tbody>

        </table>
    </div>
    <div class="card-footer">
        {{ $orders->links() }}
    </div>
</div>
@endsection
