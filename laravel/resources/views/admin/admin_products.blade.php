@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Proizvodi</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">+ Novi proizvod</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Naziv</th>
                <th>Kategorija</th>
                <th>Cijena</th>
                <th class="text-end">Akcije</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->naziv }}</td>
                    <td>{{ $product->kategorija->naziv ?? '-' }}</td>
                    <td>{{ number_format($product->cijena, 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Uredi</a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST" class="d-inline"
                              onsubmit="return confirm('Obrisati proizvod?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Obriši</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Nema proizvoda.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $products->links() }}
    </div>
</div>
@endsection
