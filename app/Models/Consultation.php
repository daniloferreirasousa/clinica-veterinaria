<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Animal;
use App\Models\User;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'veterinarian_id',
        'date_time',
        'status',
        'reason',
        'diagnosis',
        'prescription',
        'value',
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'value'     => 'decimal:2',
    ];


    /**
     * A consulta pertence a um animal (Paciente)
     */
    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }


    /**
     * A consulta pertence a um veterinário (User)
     */
    public function veterinarian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }
}
