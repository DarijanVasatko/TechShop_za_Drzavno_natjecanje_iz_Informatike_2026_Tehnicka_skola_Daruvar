@extends('layouts.admin')

@section('title', 'Upravljanje Promo Kodovima')

@section('content')
<div class="container-fluid">
    <h2 class="fw-bold mb-4">Upravljanje Promo Kodovima</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Novi Promo Kod</h5>
                    <form action="{{ route('admin.promo-kodovi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kod</label>
                            <input type="text" name="kod" class="form-control" placeholder="npr. TECH20" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tip</label>
                            <select name="tip" class="form-select">
                                <option value="postotak">Postotak (%)</option>
                                <option value="fiksno">Fiksni iznos (€)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vrijednost</label>
                            <input type="number" step="0.01" name="vrijednost" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vrijedi do</label>
                            <input type="date" name="vrijedi_do" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Max. korištenja</label>
                            <input type="number" name="max_koristenja" class="form-control" placeholder="Ostavite prazno za beskonačno">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kreiraj Kod</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Postojeći Kodovi</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kod</th>
                                    <th>Tip</th>
                                    <th>Vrijednost</th>
                                    <th>Iskorišteno</th>
                                    <th>Vrijedi do</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promoKodovi as $kod)
                                <tr>
                                    <td><strong>{{ $kod->kod }}</strong></td>
                                    <td>{{ ucfirst($kod->tip) }}</td>
                                    <td>{{ $kod->vrijednost }}{{ $kod->tip == 'postotak' ? '%' : '€' }}</td>
                                    <td>{{ $kod->koristenja }} / {{ $kod->max_koristenja ?? '∞' }}</td>
                                    <td>{{ $kod->vrijedi_do ? \Carbon\Carbon::parse($kod->vrijedi_do)->format('d.m.Y.') : 'Nema limita' }}</td>
                                    <td>
                                        @if($kod->aktivno)
                                            <span class="badge bg-success">Aktivan</span>
                                        @else
                                            <span class="badge bg-danger">Deaktivan</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection