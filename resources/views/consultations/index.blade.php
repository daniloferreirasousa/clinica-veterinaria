@extends('layouts.app')

@section('content')

    <div class="container fluid px-4">
        <div class="d-flex justify-content-between align-items-center my-4">
            <h1 class="h3 text-gray-800">Consultas e Atendimentos</h1>
            <a href="{{ route('consultations.create') }}" class="btn btn-primary">
                <i class="bi bi-calendar-plus">Nova Consulta</i>
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert" arial-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('consultations.index') }}" method="get" class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Pesquisar por paciente, tutor ou veterinário..." value="{{ $search ?? '' }}">
                    </div>

                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">-- Todos os Status --</option>
                            <option value="agendada" {{ ($status ?? '') == 'agendada' ? 'selected' : '' }}>Agendada</option>
                            <option value="em_andamento" {{ ($status ?? '') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ ($status ?? '') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ ($status ?? '') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn.btn-secondary w-100">Filtrar</button>
                        @if($search || $status)
                            <a href="{{ route('consultations.index') }}" class="btn btn-outline-secondary">Limpar</a>
                        @endif
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Data / Hora</th>
                                <th>Paciente / Tutor</th>
                                <th>Veterinário</th>
                                <th>Motivo</th>
                                <th>Satatus</th>
                                <th>Valor</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($consultations as $consul)  
                                <tr>
                                    <td class="fw-bold">{{ $consul->date_time->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $consul->animal->name }}</strong>
                                        <small class="text-muted">({{ $consul->animal->specie->name }})</small>
                                        <br>
                                        <small class="text-secondary">Tutor: {{ $consul->animal->tutor->name }}</small>
                                    </td>
                                    <td>{{ $consul->veterinarian->name }}</td>
                                    <td>{{ Str::limit($consul->reason, 30) }}</td>
                                    <td>
                                        @switch($consul->status)
                                            @case('agendada')
                                                <span class="badge bg-warning text-dark">Agendada</span>
                                                @break
                                            @case('em_andamento')
                                                <span class="badge bg-info text-white">Em Andamento</span>
                                                @break
                                            @case('concluida')
                                                <span class="badge bg-success">Concluída</span>
                                                @break
                                            @case('cancelada')
                                                <span class="badge bg-danger">Cancelada</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>R$ {{ number_format($consul->value, 2, ',', '.') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('consultations.show', $consul) }}" class="btn btn-sm btn-info text-white me-1">Ver</a>
                                        <a href="{{ route('consultations.edit', $consul) }}" class="btn btn-sm btn-warning me-1">Editar/Atender</a>
                                        <form action="{{ route('consultations.destroy', $consul) }}" method="POST" class="d-inline form-delete" data-resource="esta consulta">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-delete">Excluir</button>

                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted py-4 text-center">Nenhuma consulta registrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $consultations->links() }}
                </div>
            </div>
        </div>
    </div>


@endsection