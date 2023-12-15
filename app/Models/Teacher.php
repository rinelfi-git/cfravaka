<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['name', 'address', 'id_number', 'phone', 'email'];

    // Casts - Complet
    protected $casts = [
        'name'      => 'string',  // Cast en tant que string
        'address'   => 'string',  // Cast en tant que string
        'id_number' => 'string',  // Cast en tant que string
        'phone'     => 'string',  // Cast en tant que string
        'email'     => 'string',  // Cast en tant que string
    ];

    // Relations
    public function groupSessions() {
        return $this->hasMany(GroupSession::class); // Assurez-vous que Session existe et est correctement défini
    }

    // Si RollCall est une autre entité
    public function rollCalls() {
        return $this->hasMany(RollCall::class); // Assurez-vous que RollCall existe et est correctement défini
    }
}
