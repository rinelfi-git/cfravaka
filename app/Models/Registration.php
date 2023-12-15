<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['operation_date', 'amount'];

    // Casts - Complet
    protected $casts = [
        'operation_date' => 'date',    // Cast en tant que date
        'amount' => 'integer',         // Cast en tant que integer
    ];

    // Relations
    public function session() {
        return $this->belongsTo(Session::class); // Si chaque enregistrement est lié à une session spécifique
    }

    public function student() {
        return $this->belongsTo(Student::class); // Si chaque enregistrement est lié à un étudiant spécifique
    }

    public function level() {
        return $this->belongsTo(Level::class); // Si chaque enregistrement est lié à un niveau spécifique
    }

    public function trainingTypes() {
        return $this->belongsToMany(TrainingType::class, 'training_type_registrations'); // Si chaque enregistrement est lié à un type de formation spécifique
    }

    public function paymentHistories() {
        return $this->hasMany(PaymentHistory::class); // Si chaque enregistrement a plusieurs historiques de paiement
    }
}
