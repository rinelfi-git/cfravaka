<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['name'];

    // Relations
    // La relation exacte dépend de la structure des autres modèles et de leurs relations.
    // Par exemple, si Group est lié à GroupSessions :
    public function sessions() {
        return $this->belongsToMany(Session::class, 'group_sessions'); // Assurez-vous que Session existe et est correctement défini
    }
}
