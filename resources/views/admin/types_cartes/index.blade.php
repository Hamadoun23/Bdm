@extends('layouts.app')

@section('title', 'Types de cartes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Types de cartes</h4>
    <a href="{{ route('admin.types-cartes.create') }}" class="btn btn-primary">Nouveau type</a>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th class="text-end">Prix (FCFA)</th>
                    <th>Actif</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($types as $t)
                <tr>
                    <td><code>{{ $t->code }}</code></td>
                    <td class="text-end">{{ number_format($t->prix) }}</td>
                    <td>@if($t->actif)<span class="badge bg-success">Oui</span>@else<span class="badge bg-secondary">Non</span>@endif</td>
                    <td>
                        <a href="{{ route('admin.types-cartes.edit', $t) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="{{ route('admin.types-cartes.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce type ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
