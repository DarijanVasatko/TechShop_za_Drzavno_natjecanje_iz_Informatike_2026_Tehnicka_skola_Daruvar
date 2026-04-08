@extends('layouts.app')

@section('title', 'Spremljene konfiguracije - TechShop')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-bookmark-check text-primary me-2"></i>
            Spremljene konfiguracije
        </h2>
        <a href="{{ route('pc-builder.new') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nova konfiguracija
        </a>
    </div>

    @if($configurations->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Nemate spremljenih konfiguracija</h5>
            <p class="text-muted">Kreirajte novu konfiguraciju i spremite je za kasnije.</p>
            <a href="{{ route('pc-builder.new') }}" class="btn btn-primary">
                <i class="bi bi-pc-display me-1"></i> Konfiguriraj PC
            </a>
        </div>
    </div>
    @else
    <div class="row">
        @foreach($configurations as $config)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">{{ $config->naziv ?? 'Bez naziva' }}</h5>
                    <small class="text-muted">{{ $config->updated_at->format('d.m.Y H:i') }}</small>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($config->items as $item)
                        <li class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>
                                    <i class="bi {{ $item->tipProizvoda->ikona }} text-primary me-1"></i>
                                    {{ Str::limit($item->proizvod->Naziv, 25) }}
                                </span>
                                <span class="text-muted">{{ number_format($item->cijena_u_trenutku, 2) }} €</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Ukupno:</span>
                        <span class="h5 mb-0 text-primary">{{ number_format($config->ukupna_cijena, 2) }} €</span>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pc-builder.load', $config->id) }}" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-folder2-open me-1"></i> Učitaj
                        </a>
                        <form action="{{ route('pc-builder.delete', $config->id) }}" method="POST" onsubmit="return confirm('Jeste li sigurni da želite obrisati ovu konfiguraciju?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
