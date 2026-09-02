@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card mb-4 border-start border-primary border-4 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 mb-1 fw-bold text-dark">Histórico Clínico: {{ $animal->name }}</h2>
                    <p class="text-muted mb-0">
                        <strong>Tutor: </strong>{{ $animal->tutor->name }} | 
                        <strong>Espécie: </strong>{{ $animal->specie->name }} |
                        <strong>Raça: </strong>{{ $animal->race->name ?? 'S.R.D' }}
                    </p>
                </div>
                <a href="{{ route('animals.index') }}" class="btn btn-secondary btn-sm fw-bold">Voltar ao Módulo</a>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body bg-white rounded">
                <form action="{{ route('animals.history', $animal->id) }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label small fw-bold">Data Inicial</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="finish_date" class="form-label small fw-bold">Data Final</label>
                        <input type="date" name="finish_date" id="finish_date" class="form-control form-control-sm" value="{{ request('finish_date') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="register_type" class="form-label small fw-bold">Tipo de Registro</label>
                        <select name="register_type" id="register_type" class="form-select form-select-sm">
                            <option value="">-- Selecione um Tipo --</option>
                            <option value="consulta" @selected(request('register_type') == 'consulta')>Consultas</option>
                            <option value="exame" @selected(request('register_type') == 'exame')>Exames</option>
                            <option value="vacina" @selected(request('register_type') == 'vacina')>Vacinas</option>
                            <option value="receita" @selected(request('register_type') == 'receita')>Receitas</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Renderização dos Blocos do Histórico -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                @if($timelineSorted->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x display-4 text-muted"></i>
                        <p class="text-muted mt-2">Nenhum evento clínico localizado para este prontuário.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush">
                        @foreach($timelineSorted as $event)
                            <div class="list-group-item p-3 mb-3 boder rounded shadow-sm bg-white">
                                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                    <div>
                                        @if($event['type'] === 'consulta')
                                            <span class="badge bg-primary px-2 py-1">Consulta</span>
                                        @elseif($event['type'] === 'exame')
                                            <span class="badge bg-info text-dark px-2 py-1">Exame</span>
                                        @elseif($event['type'] === 'vacina')
                                            <span class="badge bg-success px-2 py-1">Vacinação</span>
                                        @elseif($event['type'] === 'receita')
                                            <span class="badge bg-warning text-dark px-2 py-1">Receita</span>
                                        @endif
                                        <strong class="ms-2 text-dark fs-6">{{ $event['title'] }}</strong>
                                    </div>
                                    <small class="text-muted fw-bold">{{ $event['date_time']->format('d/m/Y H:i') }}</small>
                                </div>

                                <p class="mb-2 text-muted small">
                                    <strong>Profissional Responsável: </strong>{{ $event['veterinarian'] }}
                                </p>

                                <!-- Detalhamento de acordo com o tipo do Evento -->
                                <div class="p-3 bg-light rounded border-start border-secondary border-3">
                                    @if($event['type'] === 'consulta')
                                        <p class="mb-1">
                                            <strong>Queixa/Motivo: </strong>
                                            {{ $event['data']->reason }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Diagnóstico Registrado: </strong>
                                            {{ $event['data']->diagnosis ?? 'Prontuário sem diagnóstico cadastrado.' }}
                                        </p>
                                    @elseif($event['type'] === 'exame')
                                        <p class="mb-1">
                                            <strong>Laboratório: </strong>
                                            {{ $event['data']->laboratory ?? 'Não informado' }}
                                        </p>

                                        <a href="{{ asset('storage/' . $event['data']->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="bi bi-file-earmark-arrow-down"></i> Visualizar Laudo Anexo
                                        </a>
                                    @elseif($event['type'] === 'vacina')
                                        <p class="mb-1">
                                            <strong>Fabricante/Lote: </strong>
                                            {{ $event['data']->manufacturer ?? 'N/A' }} | {{ $event['data']->batch ?? 'N/A' }}
                                        </p>

                                        <p class="mb-0 text-success small">
                                            <strong>Retorno / Próxima Dose: </strong>
                                            {{ $event['data']->next_dose_date ? date('d/m/Y', strtotime($event['data']->next_dose_date)) : 'Dose Única / Concluído' }}
                                        </p>
                                    @elseif($event['type'] === 'receita')
                                        <span class="fw bold d-block mb-1 text-secondary">
                                            Medicamentos Prescritos na Ficha: 
                                        </span>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($event['data']->items as $item)
                                                <li>
                                                    <strong>{{ $item->medication }}</strong>
                                                    - {{ $item->dosage }} (Administrar de {{ $item->frequency }} por {{ $item->duration }})
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection