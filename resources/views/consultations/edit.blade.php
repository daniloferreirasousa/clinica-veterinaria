@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 my-4 text-gray-800">Atendimento / Editar consulta #{{ $consultation->id }}</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('consultations.update', $consultation) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- O animal já está fixado nesta consulta, apenas informamos na tela --}}
                <input type="hidden" name="animal_id" value="{{ $consultation->animal_id }}">

                <div class="alert alert-info">
                    <strong>Paciente:</strong> {{ $consultation->animal->name }} | 
                    <strong>Tutor:</strong> {{ $consultation->animal->tutor->name }}
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="veterinarian_id" class="form-label">Veterinário <span class="text-danger">*</span></label>
                        <select name="veterinarian_id" id="veterinarian_id" class="form-select @error('veterinarian_id') is-invalid @enderror" required>
                            @foreach($veterinarians as $vet)
                                <option value="{{ $vet->id }}" {{ old('veterinarian_id', $consultation->veterinarian_id) == $vet->id ? 'selected' : '' }}>
                                    {{ $vet->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('veterinarian_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date_time" class="form-label">Data e Hora <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="date_time" id="date_time" class="form-control @error('date_time') is-invalid @enderror" value="{{ old('date_time', $consultation->date_time->format('Y-m-d\TH:i')) }}" required>
                        @error('date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status do Atendimento <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="agendada" {{ old('status', $consultation->status) == 'agendada' ? 'selected' : '' }}>Agendada</option>
                            <option value="em_andamento" {{ old('status', $consultation->status) == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="concluida" {{ old('status', $consultation->status) == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="cancelada" {{ old('status', $consultation->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="reason" class="form-label">Motivo Principal <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="2" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason', $consultation->reason) }}</textarea>
                        @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="diagnosis" class="form-label">Diagnóstico / Exames Clínicos</label>
                        <textarea name="diagnosis" id="diagnosis" rows="4" class="form-control @error('diagnosis') is-invalid @enderror" placeholder="Registro dos achados clínicos e diagnósticos...">{{ old('diagnosis', $consultation->diagnosis) }}</textarea>
                        @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="prescription" class="form-label">Prescrição Médica / Receita</label>
                        <textarea name="prescription" id="prescription" rows="4" class="form-control @error('prescription') is-invalid @enderror" placeholder="Medicamentos, dosagens e instruções...">{{ old('prescription', $consultation->prescription) }}</textarea>
                        @error('prescription')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="value" class="form-label">Value Final (R$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="value" id="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $consultation->value) }}" required>
                        @error('value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('consultations.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Atendimento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection