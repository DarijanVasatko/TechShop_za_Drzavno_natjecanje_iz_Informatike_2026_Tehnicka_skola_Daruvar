@extends('layouts.admin')

@section('content')
<h4 class="mb-3">Korisnici</h4>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ime i prezime</th>
                <th>E-mail</th>
                <th>Telefon</th>
                <th>Admin</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->telefon }}</td>
                    <td>{{ $user->is_admin ? 'Da' : 'Ne' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Nema korisnika.</td>
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
