@extends('layouts.admin')

@section('title', 'Korisnici — Admin')

@section('content')

<h2 class="fw-bold mb-3">
    <i class="bi bi-people me-2"></i> Korisnici
</h2>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Ime i prezime</th>
                    <th>Email</th>
                    <th>Telefon</th>
                    <th>Broj narudžbi</th>
                    <th>Admin</th>
                    <th class="text-end">Akcija</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>#{{ $u->id }}</td>
                        <td>{{ $u->full_name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->telefon ?? '—' }}</td>

                        <td>{{ $u->narudzbe_count }}</td>

                        <td>{{ $u->is_admin ? 'Da' : 'Ne' }}</td>

                        <td class="text-end">
                            <a href="{{ route('admin.users.show', $u) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detalji
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Nema registriranih korisnika.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>

@endsection
