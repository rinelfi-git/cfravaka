<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['amount', 'operation_date'];

    // Casts - Complet
    protected $casts = [
        'amount' => 'integer',         // Cast en tant que integer
        'operation_date' => 'datetime', // Cast en tant que datetime
    ];

    // Relations
    public function registration() {
        return $this->belongsTo(Registration::class); // Si chaque historique de paiement est lié à une inscription spécifique
    }
}
