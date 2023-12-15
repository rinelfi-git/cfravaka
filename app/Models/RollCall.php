<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollCall extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['date', 'delay', 'is_absent'];

    // Casts - Complet
    protected $casts = [
        'date' => 'datetime', // Cast en tant que datetime
        'delay' => 'integer', // Cast en tant que integer
        'is_absent' => 'boolean', // Cast en tant que boolean
    ];

    // Relations
    public function teacher() {
        return $this->belongsTo(Teacher::class); // Assurez-vous que Teacher existe et est correctement défini
    }

    public function students() {
        return $this->belongsTo(Student::class); // Si les appels concernent plusieurs étudiants
    }

    public function groupSession() {
        return $this->belongsTo(GroupSession::class); // Si chaque appel est lié à une session spécifique
    }
}
