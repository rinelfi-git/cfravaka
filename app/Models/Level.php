<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['name'];

    // Relations
    public function students() {
        return $this->hasMany(Student::class); // Si chaque niveau est associé à plusieurs étudiants
    }

    public function registrations() {
        return $this->hasMany(Registration::class); // Si les inscriptions sont liées à des niveaux spécifiques
    }
}
