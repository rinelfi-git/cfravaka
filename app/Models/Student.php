<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['name', 'email', 'phone', 'test_date'];

    // Casts - Complet
    protected $casts = [
        'name' => 'string',       // Cast en tant que string
        'email' => 'string',      // Cast en tant que string
        'phone' => 'string',      // Cast en tant que string
        'test_date' => 'date',    // Cast en tant que date
    ];

    // Relations
    public function sessions() {
        return $this->belongsToMany(Session::class, 'registrations'); // Si vous avez une table pivot 'registrations' pour les sessions inscrites
    }

    public function rollCalls() {
        return $this->hasMany(RollCall::class); // Si les étudiants sont liés aux appels de présence
    }

    public function level() {
        return $this->belongsTo(Level::class); // Si les étudiants sont associés à un niveau
    }

    public function partners() {
        return $this->belongsToMany(Partner::class, 'partner_students'); // Si les étudiants sont associés à des partenaires
    }

    public function groupSessions() {
        return $this->belongsToMany(GroupSession::class, 'group_session_students');
    }
}
