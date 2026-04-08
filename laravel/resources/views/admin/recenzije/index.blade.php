@extends('layouts.admin')
@section('content')
<h4 class="mb-3">Recenzije</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
            <tr>
                <th>Proizvod</th>
                <th>Korisnik</th>
                <th>Ocjena</th>
                <th>Komentar</th>
                <th>Status</th>
                <th>Datum</th>
                <th class="text-end">Akcije</th>
            </tr>
            </thead>
            <tbody>
@forelse($recenzije as $recenzija)
    <tr>
        <td>
            <a href="{{ route('proizvod.show', $recenzija->proizvod_id) }}" class="text-decoration-none">
                {{ \Illuminate\Support\Str::limit($recenzija->proizvod->Naziv ?? '-', 30) }}
            </a>
        </td>
        <td>{{ $recenzija->user->full_name ?? $recenzija->user->email ?? '-' }}</td>
        <td class="text-nowrap">
            @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= $recenzija->ocjena ? '-fill text-warning' : ' text-muted' }}"></i>
            @endfor
        </td>
        <td style="max-width: 250px;">
            @if($recenzija->komentar)
                <span class="text-truncate d-inline-block" style="max-width: 250px;">{{ $recenzija->komentar }}</span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>
            @if($recenzija->odobrena)
                <span class="badge bg-success px-3 py-2">Odobrena</span>
            @else
                <span class="badge bg-warning text-dark px-3 py-2">Na čekanju</span>
            @endif
        </td>
        <td class="text-nowrap">{{ $recenzija->created_at->format('d.m.Y H:i') }}</td>
        <td class="text-end text-nowrap">
            <button class="btn btn-sm btn-outline-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#recenzijaModal{{ $recenzija->id }}"
                    title="Detalji">
                <i class="bi bi-eye"></i>
            </button>

            @if(!$recenzija->odobrena)
                <form action="{{ route('admin.recenzije.approve', $recenzija) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-outline-success" title="Odobri">
                        <i class="bi bi-check-circle"></i>
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.recenzije.reject', $recenzija) }}"
                  method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Jeste li sigurni da želite obrisati ovu recenziju?');"
                        title="Obriši">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center text-muted py-4">
            Nema recenzija.
        </td>
    </tr>
@endforelse
</tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $recenzije->links() }}
    </div>
</div>

@foreach($recenzije as $recenzija)
<div class="modal fade" id="recenzijaModal{{ $recenzija->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detalji recenzije</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Proizvod</label>
                    <div>
                        <a href="{{ route('proizvod.show', $recenzija->proizvod_id) }}" class="text-decoration-none fw-bold">
                            {{ $recenzija->proizvod->Naziv ?? '-' }}
                        </a>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Korisnik</label>
                    <div>{{ $recenzija->user->full_name ?? $recenzija->user->email ?? '-' }}</div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Ocjena</label>
                    <div class="fs-5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $recenzija->ocjena ? '-fill text-warning' : ' text-muted' }}"></i>
                        @endfor
                        <span class="ms-2 text-dark fw-bold">{{ $recenzija->ocjena }}/5</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small text-uppercase fw-semibold">Komentar</label>
                    <div class="p-3 bg-light rounded-2 mt-1" style="white-space: pre-wrap;">{{ $recenzija->komentar ?: 'Nema komentara.' }}</div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <label class="text-muted small text-uppercase fw-semibold">Status</label>
                        <div>
                            @if($recenzija->odobrena)
                                <span class="badge bg-success px-3 py-2">Odobrena</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2">Na čekanju</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small text-uppercase fw-semibold">Datum</label>
                        <div>{{ $recenzija->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if(!$recenzija->odobrena)
                    <form action="{{ route('admin.recenzije.approve', $recenzija) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i> Odobri
                        </button>
                    </form>
                @endif
                <form action="{{ route('admin.recenzije.reject', $recenzija) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger"
                            onclick="return confirm('Jeste li sigurni da želite obrisati ovu recenziju?');">
                        <i class="bi bi-trash me-1"></i> Obriši
                    </button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
