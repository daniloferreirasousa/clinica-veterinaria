@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestão de Raças</h2>
        <a href="{{ route('races.create') }}" class="btn btn-primary">Nova Raça</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('races.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por raça ou espécie..." value="{{ $search }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Raça</th>
                        <th>Espécie</th>
                        <th>Animais Cadastrados</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($races as $race)
                        <tr>
                            <td>{{ $race->id }}</td>
                            <td><strong>{{ $race->name }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $race->specie->name }}</span></td>
                            <td><span class="badge bg-info text-dark">{{ $race->animais_count }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('races.edit', $race) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('races.destroy', $race) }}" method="POST" class="d-inline form-delete" data-resource="esta raça">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Nenhuma raça cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $races->links() }}</div>
</div>
@endsection