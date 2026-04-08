@extends('layouts.admin')

@section('title', 'Narudžbe — Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1"><i class="bi bi-receipt me-2"></i> Narudžbe</h2>
        <p class="text-muted mb-0">Pregled svih narudžbi korisnika.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Kupac</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th class="text-end">Ukupno</th>
                    <th class="text-end">Akcija</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>#{{ $order->Narudzba_ID }}</td>

                        <td>
                            {{ $order->user->full_name ?? $order->user->email ?? 'N/A' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}
                        </td>

                        <td>
                            @php
                                $color = match($order->Status) {
                                    'U obradi', 'Na čekanju', 'Čeka plaćanje pouzećem' => 'warning',
                                    'Poslano' => 'info',
                                    'Dostavljeno' => 'primary',
                                    'Dovršena' => 'success',
                                    'Otkazana' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp

                            <span class="badge bg-{{ $color }}">
                                {{ $order->Status }}
                            </span>
                        </td>

                        <td class="text-end">
                            {{ number_format($order->Ukupni_iznos, 2, ',', '.') }} €
                        </td>

                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i> Detalji
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Nema narudžbi u sustavu.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="card-footer bg-white border-top-0 py-3">
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
