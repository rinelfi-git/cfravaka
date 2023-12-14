<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Session extends Model {
        use HasFactory;

        protected $table = 'sessions';

        protected $fillable = [
            'label',
            'start_date',
            'end_date',
            'place',
        ];

        public function groups() {
            return $this->belongsToMany(Group::class);
        }

        public function students() {
            return $this->belongsToMany(Student::class);
        }
    }
