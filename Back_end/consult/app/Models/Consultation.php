<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;
    public function expert() {
        return $this->belongsTo(Expert::class);
    }

    public function consult_type() {
        return $this->belongsTo(ConsultType::class);
    }
}
