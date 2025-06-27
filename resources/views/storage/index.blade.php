{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Productvoorraad Overzicht</h1>

    <form method="GET" action="{{ route('products.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="barcode" class="form-control" placeholder="Zoek op streepjescode" value="{{ request('barcode') }}">
            <button class="btn btn-primary" type="submit">Zoeken</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'barcode', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Streepjescode
                    </a>
                </th>
                <th>
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Productnaam
                    </a>
                </th>
                <th>
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'category', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Categorie
                    </a>
                </th>
                <th>
                    <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'quantity', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Aantal
                    </a>
                </th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $product->barcode }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->quantity }}</td>
                <td>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Wijzigen</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?')">Verwijderen</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Geen producten gevonden.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('products.create') }}" class="btn btn-success">Nieuw product toevoegen</a>
</div>
@endsection