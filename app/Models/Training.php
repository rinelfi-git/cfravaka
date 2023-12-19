<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Factories\HasFactory;
	use Illuminate\Database\Eloquent\Model;
	
	class Training extends Model {
		use HasFactory;
		
		// Fillable properties
		protected $fillable = ['name'];
		
		public static function getTrainingFormatedList() {
			return self::doesntHave('partner')
				->with('trainingTypes')
				->get()
				->flatMap(function ($training) {
					return $training->trainingTypes->map(function ($subCategory) use ($training) {
						$constructName = $training->name . " (";
						switch ($subCategory->modality) {
							case 'En ligne':
								$constructName .= "On|";
								break;
							default:
								$constructName .= "Off|";
								break;
						}
						switch ($subCategory->formula) {
							case 'Intensif':
								$constructName .= "In|";
								break;
							default:
								$constructName .= "Ex|";
								break;
						}
						switch ($subCategory->convenience) {
							case 'En particulier':
								$constructName .= "Si|";
								break;
							default:
								$constructName .= "Gr|";
								break;
						}
						$constructName .= $subCategory->hourly_volume . ")";
						return [
							'id'    => $subCategory->id,
							'name'  => $constructName,
							'price' => $subCategory->price
						];
					});
				});
		}
		
		// Relations
		public function partner() {
			return $this->hasOne(Partner::class); // Si chaque formation est associée à un partenaire
		}
		
		public function trainingTypes() {
			return $this->hasMany(TrainingType::class); // Si chaque formation est liée à un type de formation
		}
	}
