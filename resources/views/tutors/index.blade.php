@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciamento de Tutores</h2>
    <a href="{{ route('tutors.create') }}" class="btn btn-primary">Novo Tutor</a>
</div>

<!-- Barra de Pesquisa -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('tutors.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome ou CPF..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-secondary">Pesquisar</button>
            <a href="{{ route('tutors.index') }}" class="btn btn-outline-secondary">Limpar</a>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tutors as $tutor)
                        <tr>
                            <td>{{ $tutor->name }}</td>
                            <td>{{ $tutor->cpf }}</td>
                            <td>{{ $tutor->phone }}</td>
                            <td>{{ $tutor->address }}</td>
                            <td class="text-end">
                                <a href="{{ route('tutors.show', $tutor) }}" class="btn btn-sm btn-info text-white">Ver</a>
                                <a href="{{ route('tutors.edit', $tutor) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('tutors.destroy', $tutor) }}" method="POST" class="d-inline form-delete" data-resource="este tutor">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Nenhum tutor encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tutors->hasPages())
        <div class="card-footer">
            {{ $tutors->links() }}
        </div>
    @endif
</div>
@endsection

