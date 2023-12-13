<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class Student extends Model {
		use HasFactory;
		
		protected $table = 'students';
		
		protected $fillable = [
			'name',
			'email',
			'phone',
			'test_date',
			'test_result'
		];
		
		protected $casts = [
			'test_date' => 'datetime:Y-m-d\TH:i:sP',
		];
		
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
