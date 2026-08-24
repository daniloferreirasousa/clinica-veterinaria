@extends('layouts.app')

@section('content')
   <div class="container-fluid px-4">
    <h1 class="h3 my-4 text-gray-800">Agendar Nova Consulta</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('consultations.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- Passo 1: Seleção do Tutor --}}
                    <div class="col-md-6">
                        <label for="tutor_id" class="form-label">1. Selecione o Tutor <span class="text-danger">*</span></label>
                        <select name="tutor_id" id="tutor_id" class="form-select @error('animal_id') is-invalid @enderror" required>
                            <option value="">-- Escolha um Tutor --</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}" {{ old('tutor_id') == $tutor->id ? 'selected' : '' }}>
                                    {{ $tutor->name }} (CPF: {{ $tutor->cpf }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Passo 2: Paciente (Carregado via AJAX) --}}
                    <div class="col-md-6">
                        <label for="animal_id" class="form-label">2. Paciente (Pet) <span class="text-danger">*</span></label>
                        <select name="animal_id" id="animal_id" class="form-select @error('animal_id') is-invalid @enderror" required disabled>
                            <option value="">-- Primeiro selecione um Tutor --</option>
                        </select>
                        @error('animal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="veterinarian_id" class="form-label">Veterinário Responsável <span class="text-danger">*</span></label>
                        <select name="veterinarian_id" id="veterinarian_id" class="form-select @error('veterinarian_id') is-invalid @enderror" required>
                            <option value="">-- Selecione o Veterinário --</option>
                            @foreach($veterinarians as $vet)
                                <option value="{{ $vet->id }}" {{ old('veterinarian_id') == $vet->id ? 'selected' : '' }}>
                                    {{ $vet->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('veterinarian_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="date_time" class="form-label">Data e Hora <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="date_time" id="date_time" class="form-control @error('date_time') is-invalid @enderror" value="{{ old('date_time') }}" required>
                        @error('date_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Inicial <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="agendada" {{ old('status') == 'agendada' ? 'selected' : '' }}>Agendada</option>
                            <option value="em_andamento" {{ old('status') == 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label for="reason" class="form-label">Motivo Principal / Queixa <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" rows="3" class="form-control @error('reason') is-invalid @enderror" placeholder="Descreva os sintomas ou reason da consulta..." required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="value" class="form-label">Valor Estimado (R$) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="value" id="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', '150.00') }}" required>
                        @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('consultations.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Agendar Consulta</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT FETCH API / AJAX --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tutorSelect = document.getElementById('tutor_id');
        const animalSelect = document.getElementById('animal_id');

        tutorSelect.addEventListener('change', function () {
            const tutorId = this.value;

            animalSelect.innerHTML = '<option value="">Carregando pacientes...</option>';
            animalSelect.disabled = true;

            if (!tutorId) {
                animalSelect.innerHTML = '<option value="">-- Primeiro selecione um Tutor --</option>';
                return;
            }

            // Requisição assíncrona com Fetch API para a roa JSON interna
            fetch(`/api-local/tutors/${tutorId}/animals`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro na requisição');
                    return response.json();
                })
                .then(animals => {
                    animalSelect.innerHTML = '<option value="">-- Selecione o Paciente --</option>';

                    if (animals.length === 0) {
                        animalSelect.innerHTML = '<option value="">Este tutor não possui animais cadastrados</option>';

                        return;
                    }

                    animals.forEach(animal => {
                        const option = document.createElement('option');
                        option.value = animal.id;
                        option.textContent = `${animal.name} (${animal.specie.name})`;
                        animalSelect.appendChild(option);
                    });

                    animalSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Erro:', error);
                    animalSelect.innerHTML = '<option value="">Erro ao carregar animais</option>';
                });
        });
    });
</script>
@endsection