@extends('layouts.app')

@section('title', 'Gestion des agences')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Agences</h4>
    <a href="{{ route('admin.agences.create') }}" class="btn btn-primary">Nouvelle agence</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-end" style="width:5rem">N°</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agences as $a)
                <tr>
                    <td class="text-end text-muted">{{ $a->ordre }}</td>
                    <td>{{ $a->nom }}</td>
                    <td>
                        <a href="{{ route('admin.agences.edit', $a) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="{{ route('admin.agences.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette agence ?')">
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
