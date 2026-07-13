<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collaboration extends Model
{
    public $timestamps = false;

    protected $fillable = ['lecturer_id_1', 'lecturer_id_2', 'collaboration_count', 'shared_publications'];

    protected function casts(): array
    {
        return [
            'collaboration_count' => 'integer',
            'shared_publications' => 'array',
        ];
    }

    public function lecturerOne(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id_1');
    }

    public function lecturerTwo(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class, 'lecturer_id_2');
    }
}
