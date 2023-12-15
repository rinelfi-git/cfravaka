<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingType extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['modality', 'formula', 'convenience', 'price', 'hourly_volume', 'is_monthly'];

    // Casts - Complet
    protected $casts = [
        'modality' => 'string',        // Cast en tant que string
        'formula' => 'string',         // Cast en tant que string
        'convenience' => 'string',     // Cast en tant que string
        'price' => 'integer',          // Cast en tant que integer
        'hourly_volume' => 'integer',  // Cast en tant que integer
        'is_monthly' => 'boolean',     // Cast en tant que boolean
    ];

    // Relations
    public function training() {
        return $this->belongsTo(Training::class); // Si chaque type de formation est associé à plusieurs formations
    }

    public function registrations() {
        return $this->belongsToMany(Registration::class, 'training_type_registrations'); // Si les types de formation sont liés à des inscriptions spécifiques
    }
}
