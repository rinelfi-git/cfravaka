<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model {
    use HasFactory;
    protected $table = 'registers';
    protected $fillable = [
        'date',
        'amount'
    ];
    protected $casts = [
        'date' => 'datetime:Y-m-d\TH:i:sP',
    ];
    public function formationTypes() {
        return $this->belongsToMany(FormationType::class);
    }

    public function paymentHistories() {
        return $this->belongsToMany(PaymentHistory::class);
    }

    public function level() {
        return $this->belongsTo(Level::class);
    }
}
