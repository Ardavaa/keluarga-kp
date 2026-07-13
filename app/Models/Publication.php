<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    public $timestamps = false;

    protected $fillable = ['lecturer_id', 'title', 'year'];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Lecturer::class);
    }
}
