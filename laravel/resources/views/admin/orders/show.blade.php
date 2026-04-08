@extends('layouts.admin')

@section('content')
<h4 class="mb-3">Narudžba #{{ $order->Narudzba_ID }}</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Podaci o kupcu</h5>

        <p class="mb-1"><strong>Ime i prezime:</strong>
            {{ $order->user->full_name ?? '-' }}
        </p>

        <p class="mb-1"><strong>Email:</strong>
            {{ $order->user->email }}
        </p>

        <p class="mb-1"><strong>Način plaćanja:</strong>
            {{ $order->nacinPlacanja->Naziv ?? '-' }}
        </p>

        <p class="mb-0"><strong>Datum narudžbe:</strong>
            {{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}
        </p>

        @if($order->napomena)
        <div class="alert alert-info mt-3 mb-0 py-2">
            <i class="bi bi-info-circle me-1"></i> <strong>Napomena:</strong> {{ $order->napomena }}
        </div>
        @endif

    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Proizvod</th>
                    <th class="text-center">Količina</th>
                    <th class="text-center">Cijena</th>
                    <th class="text-center">Ukupno</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->detalji as $item)
                    <tr>
                        <td>{{ $item->proizvod->Naziv ?? 'Proizvod obrisan' }}</td>
                        <td class="text-center">{{ $item->Kolicina }}</td>
                        <td class="text-center">{{ number_format($item->Cijena, 2) }} €</td>
                        <td class="text-center fw-bold">
                            {{ number_format($item->Kolicina * $item->Cijena, 2) }} €
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer text-end">
        <h5 class="fw-bold mb-0">
            Ukupno: {{ number_format($order->Ukupni_iznos, 2) }} €
        </h5>
    </div>
</div>

<div class="card shadow-sm p-4">
    <h5 class="fw-bold mb-3">Promjena statusa narudžbe</h5>

    <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="d-flex gap-3">
        @csrf
        @method('PUT')

        <select name="Status" class="form-select w-auto">
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected($order->Status === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary">Spremi</button>
    </form>

    <hr class="my-4">

    @if($order->Status !== 'Otkazana')
        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')

            <button class="btn btn-danger" onclick="return confirm('Jeste li sigurni da želite otkazati narudžbu?');">
                Otkaži narudžbu
            </button>
        </form>
    @endif

    @if($order->Status !== 'Narudžba završena' && $order->Status !== 'Otkazana')
        <form action="{{ route('admin.orders.close', $order) }}" method="POST" class="d-inline">
            @csrf
            @method('PUT')

            <button class="btn btn-success">
                Zatvori narudžbu
            </button>
        </form>
    @endif
</div>

@endsection
