<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Student extends Model {
        use HasFactory;

        protected $table = 'students';

        public function partner() {
            return $this->belongsTo(Partner::class);
        }

        public function appeals() {
            return $this->hasMany(Appeal::class);
        }

        public function sessions() {
            return $this->belongsToMany(Session::class);
        }
    }
