<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    public $timestamps = false;

    protected $fillable = ['lecturer_id', 'recommended_lecturer_id', 'score', 'reasons'];

    protected function casts(): array
    {
        return [
            'score' => 'float',
            'reasons' => 'array',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function recommendedLecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'recommended_lecturer_id');
    }
}
