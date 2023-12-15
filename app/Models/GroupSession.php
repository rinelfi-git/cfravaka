<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSession extends Model
{
    use HasFactory;

    public function rollCalls() {
        return $this->hasMany(RollCall::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'group_session_students');
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function timeRanges() {
        return $this->hasMany(TimeRange::class);
    }
}
