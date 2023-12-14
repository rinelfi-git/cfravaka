<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class FormationType extends Model {
		use HasFactory;
		
		protected $table = 'formation_types';
		protected $fillable = [
			'name'
		];
		
		public function partner() {
			return $this->hasOne(Partner::class);
		}
		
		public function formationSubCategories() {
			return $this->hasMany(FormationSubCategory::class);
		}
		
		public function registers() {
			return $this->belongsToMany(Register::class);
		}
		
		public function scopeWithPartnerSearch($searchTerm) {
			return $this->where('name', 'like', "%{$searchTerm}%")
			             ->orWhereHas('partner', function ($query) use ($searchTerm) {
				             $query->where('name', 'like', "%{$searchTerm}%");
			             });
		}
	}
