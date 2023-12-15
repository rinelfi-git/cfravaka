<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['name'];

    // Relations
    public function partner() {
        return $this->hasOne(Partner::class); // Si chaque formation est associée à un partenaire
    }

    public function trainingTypes() {
        return $this->hasMany(TrainingType::class); // Si chaque formation est liée à un type de formation
    }
}
