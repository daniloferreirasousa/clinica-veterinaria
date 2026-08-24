@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pacientes (Animais)</h2>
        <a href="{{ route('animals.create') }}" class="btn btn-primary">Novo Paciente</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('animals.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por paciente, espécie, raça ou tutor..." value="{{ $search }}">
            <button class="btn btn-outline-secondary" type="submit">Buscar</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nome do Pet</th>
                        <th>Espécie / Raça</th>
                        <th>Sexo</th>
                        <th>Tutor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($animals as $animal)
                        <tr>
                            <td>{{ $animal->id }}</td>
                            <td><strong>{{ $animal->name }}</strong></td>
                            <td>
                                <span class="badge bg-secondary">{{ $animal->specie->name }}</span>
                                <small class="text-muted">{{ $animal->race ? '('.$animal->race->name.')' : '' }}</small>
                            </td>
                            <td>{{ ($animal->gender) === 'male' ? 'Macho' : 'Fêmea' }}</td>
                            <td>{{ $animal->tutor->name }}</td>
                            <td class="text-end">
                                <a href="{{ route('animals.show', $animal) }}" class="btn btn-sm btn-info text-white">Ver</a>
                                <a href="{{ route('animals.edit', $animal) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('animals.destroy', $animal) }}" method="POST" class="d-inline form-delete" data-resource="este animal">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Nenhum paciente cadastrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $animals->links() }}</div>
</div>
@endsection