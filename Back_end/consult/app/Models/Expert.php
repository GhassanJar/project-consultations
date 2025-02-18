<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\ErrorHandler\Collecting;
use PhpParser\Node\Expr\Array_;

class Expert extends Model
{
    use HasFactory;

    protected $gaurded = [];

    protected $with = [
        // 'user'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
        'user_id'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function work_days()
    {
        return $this->hasMany(WorkDay::class);
    }

    protected static function scopeUser($query, $user_id) { // get expert's user
        $query->whereHas(
            'user',
            fn($query) => $query->where('id', $user_id)
        )->first();
    }

    public function favorableBy() {
        return $this->hasMany(Favorite::class);
    }

    protected function scopeFilter($query,array $filters)
    {
        // Search
        $query->when(
            $filters['search'] ?? false,
            fn($query, $search_phrase) =>
            $query
                ->whereHas(  // searching for matches in expert's user's full_name
                    'user', fn($query) =>
                    $query
                        ->where('name_en', 'like', '%' . $search_phrase . '%')
                        ->orWhere('name_ar', 'like', '%' . $search_phrase . '%')
                )
                ->orWhereHas( // searching for matches in expert's types of consultations
                    'consultations', fn($query) =>
                    $query
                        ->where('type_en', 'like', '%' . $search_phrase . '%')
                        ->orWhere('type_ar', 'like', '%' . $search_phrase . '%')
                )
        );

        // Filter by Type of Consultations
        $query->
            when(
                $filters['consulttype'] ?? false,
                fn($query, $consulttype) =>
                $query->
                    whereHas(
                        'consultations', fn($query) =>
                        $query
                            ->where('type_en', $consulttype)
                            ->orWhere('type_ar', $consulttype)
                    )
            );
       
            
    }

}