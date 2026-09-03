@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Editar Raça</h2>

    <div class="card mt-3 col-md-6">
        <div class="card-body">
            <form action="{{ route('races.update', $race) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="specie_id" class="form-label">Espécie <span class="text-danger">*</span></label>
                    <select name="specie_id" id="specie_id" class="form-select @error('specie_id') is-invalid @enderror" required>
                        <option value="">-- Selecione a Espécie --</option>
                        @foreach($species as $specie)
                            <option value="{{ $specie->id }}" {{ old('specie_id', $race->specie_id) == $specie->id ? 'selected' : '' }}>
                                {{ $specie->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specie_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">name da Raça <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $race->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('races.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>
@endsection