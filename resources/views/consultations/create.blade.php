@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <div class="card-body">
            <form action="{{ route('consultas.store') }}" method="post">
                @csrf

                <div class="row g-3">
                    {{-- Passo 1: Seleção do Tutor --}}
                    <div class="col-md-6">
                        <label for="tutor_id" class="form-label">1. Selecione o Tutor <span class="text-danger">*</span></label>

                        <select name="tutor_id" id="tutor_id" class="form-select @error('animal_id') is_invalid @enderror" required>
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
                        <select name="animal_id" id="animal_id" class="form-select @error('animal_id') is_invalid @enderror" required disabled>
                            <option value="">-- Primeiro selecione um Tutor--</option>
                        </select>
                        @error('animal_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="veterinarian_id" class="form-label">Veterinário Responsável <span class="text-danger">*</span></label>
                        <select name="veterinarian_id" id="veterinarian_id" class="form-select @error('veterinaria_id') is-invalid @enderror" required>
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
                        <input type="datetime-local" name="date_time" class="form-control @error('date_time') is-invalid @enderror" value="{{ old('date_time') }}" required>
                        @error('date_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection