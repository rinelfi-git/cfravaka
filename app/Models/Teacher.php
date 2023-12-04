<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Teacher extends Model {
        use HasFactory;

        protected $table = 'teachers';

        public function sessionGroups() {
            return $this->hasMany(SessionGroup::class);
        }

        public function appeals() {
            return $this->hasMany(Appeal::class);
        }
    }
