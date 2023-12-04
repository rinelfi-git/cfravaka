<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Register extends Model {
        use HasFactory;
        protected $table='registers';
        public function formationTypes() {
            return $this->belongsToMany(FormationType::class);
        }

        public function paymentHistories() {
            return $this->belongsToMany(PaymentHistory::class);
        }
    }
