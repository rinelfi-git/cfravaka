<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['name', 'owner'];

    // Casts - Complet
    protected $casts = [
        'name' => 'string',  // Cast en tant que string
        'owner' => 'string', // Cast en tant que string
    ];

    // Relations
    public function students() {
        return $this->belongsToMany(Student::class, 'partner_students'); // Si les partenaires sont liés aux étudiants
    }

    public function training() {
        return $this->belongsTo(Training::class); // Si un partenaire propose plusieurs formations
    }
}
