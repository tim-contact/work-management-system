<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Work;

class WorkSession extends Model
{
    protected $connection = 'mysql_work';
    protected $fillable = [
        'started_at',
        'stopped_at',
        'duration'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'stopped_at' => 'datetime',
    ];

    public function work(): BelongsTo
    {
        return $this->belongsTo(Work::class);
    } 
}
