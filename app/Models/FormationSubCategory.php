<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class FormationSubCategory extends Model {
        use HasFactory;

        protected $table = 'formation_sub_categories';

        public function formationType() {
            return $this->belongsTo(FormationType::class);
        }
    }
