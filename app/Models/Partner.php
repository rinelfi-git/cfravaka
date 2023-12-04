<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Partner extends Model {
        use HasFactory;
        protected $table='partners';
        public function student() {
            return $this->hasMany(Student::class);
        }

        public function formationType() {
            return $this->belongsTo(FormationType::class);
        }
    }
