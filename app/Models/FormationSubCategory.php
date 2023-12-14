<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class FormationSubCategory extends Model {
        use HasFactory;

        protected $table = 'formation_sub_categories';
		protected $fillable = [
			'modality',
			'formula',
			'convenience',
			'time_range',
			'price',
			'is_monthly',
			'is_editable'
		];

        public function formationType() {
            return $this->belongsTo(FormationType::class);
        }
    }
