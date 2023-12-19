<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['label', 'start_date', 'end_date', 'available_place'];

    // Casts - Complet
    protected $casts = [
        'label' => 'string',            // Cast en tant que string
        'start_date' => 'date:Y-m-d',         // Cast en tant que date
        'end_date' => 'date:Y-m-d',           // Cast en tant que date
        'available_place' => 'integer', // Cast en tant que integer
    ];

    // Relations
    public function groups() {
        return $this->belongsToMany(Group::class, 'group_sessions'); // Assurez-vous que Group existe et est correctement défini
    }

    // Relations
    public function registrations() {
        return $this->hasMany(Registration::class); // Assurez-vous que Group existe et est correctement défini
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'registrations'); // Si vous avez une table pivot 'registrations' pour les étudiants inscrits
    }
}
