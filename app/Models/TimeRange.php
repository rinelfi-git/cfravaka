<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeRange extends Model {
    use HasFactory;
    // Fillable properties
    protected $fillable = ['day_of_week', 'start_time', 'end_time'];

    // Casts
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relations
    // La relation exacte dépend de la structure des autres modèles et de leurs relations.
    // Par exemple, si TimeRange est lié à GroupSessions :
    public function groupSessions() {
        return $this->belongsTo(GroupSession::class); // Assurez-vous que GroupSession existe et est correctement défini
    }
}
