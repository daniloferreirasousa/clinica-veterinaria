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
                        <tr><th>Valor:</th><td>R$ {{ number_format($consultation->vale, 2, ',', '.') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Prontuário Médico</h5>
                </div>
                <div class="card-body">
                    <h6><strong>Motivo da consultation:</strong></h6>
                    <p class="text-muted">{{ $consultation->reason }}</p>
                    <hr>

                    <h6><strong>Diagnóstico Clínico:</strong></h6>
                    <p class="text-muted">{{ $consultation->diagnosis ?? 'Nenhum diagnóstico registrado.' }}</p>
                    <hr>

                    <h6><strong>Prescrição / Tratamento:</strong></h6>
                    <p class="text-muted">{{ $consultation->prescription ?? 'Nenhuma prescrição informada.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection