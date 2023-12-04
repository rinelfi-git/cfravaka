<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class FormationType extends Model {
        use HasFactory;
        protected $table='formation_types';
        public function partner() {
            return $this->hasOne(Partner::class);
        }

        public function formationSubCategories() {
            return $this->hasMany(FormationSubCategory::class);
        }

        public function registers() {
            return $this->belongsToMany(Register::class);
        }
    }
