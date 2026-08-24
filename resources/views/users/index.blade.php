@extends('layouts.app')


@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gerenciamento de Usuários</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Novo Usuário</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome ou e-mail..." value="{{ $search ?? '' }}">
            <button class="btn btn-secondary" type="submit">Pesquisar</button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Limpar</a>
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
                        <th>E-mail</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                @if($user->status)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-danger">Inativo</span>
                                @endif
                            </td>

                            <td class="text-end">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info text-white">Ver</a>

                                 <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Editar</a>

                                 @if(auth()->user()->id !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}"
                                    method="POST" class="d-inline form-delete" data-resource="este usuário">

                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Excluir</button>

                                    </form>
                                 @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center py-4 text-muted" colspan="5">Nenhum usuário cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection