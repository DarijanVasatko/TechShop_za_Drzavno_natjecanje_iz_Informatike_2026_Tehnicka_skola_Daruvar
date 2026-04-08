@extends('layouts.app')

@section('title', 'Moje narudžbe — TechShop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center text-primary mb-5">
        <i class="bi bi-box-seam me-2"></i> Moje narudžbe
    </h2>

    @if($orders->isEmpty())
        <p class="text-muted text-center">Trenutno nemate narudžbi.</p>
    @else
        <div class="table-responsive shadow-sm rounded-4 bg-white p-3">
            <table class="table align-middle mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Datum</th>
                        <th>Ukupna cijena</th>
                        <th>Status</th>
                        <th>Način plaćanja</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ optional($order->created_at)->format('d.m.Y H:i') ?? '-' }}</td>
                            <td>{{ number_format($order->ukupna_cijena, 2) }} €</td>
                            <td><span class="badge bg-info">{{ $order->status }}</span></td>
                            <td>{{ optional($order->nacinPlacanja)->naziv ?? 'N/A' }}</td>
                            <td class="text-end">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-eye"></i> Detalji
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
