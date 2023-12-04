<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class SessionGroup extends Model {
        use HasFactory;

        protected $table = 'session_groups';

        public function teacher() {
            return $this->belongsTo(Teacher::class);
        }

        public function appeals() {
            return $this->hasMany(Appeal::class);
        }
    }
