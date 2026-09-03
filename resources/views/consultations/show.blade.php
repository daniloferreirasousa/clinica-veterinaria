@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1 class="h3 text-gray-800">Ficha da Consulta #{{ $consultation->id }}</h1>
        <div>
            <a href="{{ route('consultations.edit', $consultation) }}" class="btn btn-warning">Editar / Atender</a>
            <a href="{{ route('consultations.index') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <div class="row">
        {{-- Card: Paciente e Tutor --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informações do Paciente e Tutor</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>Nome do Pet:</th><td class="fw-bold">{{ $consultation->animal->name }}</td></tr>
                        <tr><th>Espécie / Raça:</th><td>{{ $consultation->animal->specie->name }} / {{ $consultation->animal->race->name ?? 'S/R' }}</td></tr>
                        <tr><th>Tutor:</th><td>{{ $consultation->animal->tutor->name }} (CPF: {{ $consultation->animal->tutor->cpf }})</td></tr>
                        <tr><th>Telefone:</th><td>{{ $consultation->animal->tutor->phone ?? 'Não informado' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card: Detalhes do Agendamento --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Detalhes do Agendamento</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr><th>Data / Hora:</th><td class="fw-bold">{{ $consultation->date_time->format('d/m/Y \à\s H:i') }}</td></tr>
                        <tr><th>Veterinário:</th><td>{{ $consultation->veterinarian->name }}</td></tr>
                        <tr><th>Status:</th><td><span class="badge bg-secondary">{{ ucfirst($consultation->status) }}</span></td></tr>
                        <tr><th>Valor:</th><td>R$ {{ number_format($consultation->value, 2, ',', '.') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Card: Prontuário Médico --}}
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Prontuário Médico</h5>
                </div>
                <div class="card-body">
                    <h6><strong>Motivo da Consulta:</strong></h6>
                    <p class="text-muted">{{ $consultation->reason }}</p>
                    <hr>

                    <h6><strong>Diagnóstico Clínico:</strong></h6>
                    <p class="text-muted">{{ $consultation->diagnosis ?? 'Nenhum diagnóstico registrado.' }}</p>
                    <hr>

                    <h6><strong>Prescrição / Tratamento Geral:</strong></h6>
                    <p class="text-muted">{{ $consultation->prescription ?? 'Nenhuma prescrição informada.' }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Receitas e Medicamentos Emitidos --}}
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Receitas Médicas Emitidas</h5>
                    <a href="{{ route('prescriptions.create', ['animal' => $consultation->animal->id, 'consultation_id' => $consultation->id]) }}" class="btn btn-sm btn-light text-success fw-bold">
                        + Emitir Nova Receita
                    </a>
                </div>
                <div class="card-body">
                    @if($consultation->receitas && $consultation->receitas->isNotEmpty())
                        @foreach($consultation->receitas as $receita)
                            <div class="border rounded p-3 mb-4 bg-light">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1 text-primary">Receita #{{ $receita->id }}</h6>
                                        <small class="text-muted">Data de Emissão: {{ \Carbon\Carbon::parse($receita->data)->format('d/m/Y') }}</small>
                                    </div>
                                    <a href="{{ route('receitas.show', $receita) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        Ver / Imprimir
                                    </a>
                                </div>

                                @if($receita->observacoes)
                                    <p class="small text-muted mb-2"><strong>Observações:</strong> {{ $receita->observacoes }}</p>
                                @endif

                                <h6 class="fw-bold mt-3 mb-2">Medicamentos Prescritos:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered bg-white align-middle mb-0">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Medicamento</th>
                                                <th>Dosagem</th>
                                                <th>Frequência</th>
                                                <th>Duração</th>
                                                <th>Orientações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($receita->itens as $item)
                                                <tr>
                                                    <td class="fw-bold text-dark">{{ $item->medicamento }}</td>
                                                    <td>{{ $item->dosagem }}</td>
                                                    <td>{{ $item->frequencia }}</td>
                                                    <td>{{ $item->duracao }}</td>
                                                    <td>{{ $item->orientacoes ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Nenhuma receita foi emitida/vinculada a esta consulta.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

