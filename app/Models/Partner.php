<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;

	class Partner extends Model {
		use HasFactory;

		protected $table = 'partners';
		protected $fillable = [
			'name',
			'owner'
		];

		public function students() {
			return $this->hasMany(Student::class);
		}

		public function formationType() {
			return $this->belongsTo(FormationType::class);
		}
	}
