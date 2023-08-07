<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolydockAppInstanceLog extends Model
{
    use HasFactory;

    protected $casts = [
        'data' => 'array'
    ];

    public $fillable = [
        'log',
        'level',
        'data'
    ];

    const LOG_LEVEL_INFO = 'info';
    const LOG_LEVEL_DEBUG = 'debug';
    const LOG_LEVEL_WARN = 'warn';
    const LOG_LEVEL_ERROR = 'error';

    public function polydockAppInstance()
    {
        return $this->belongsTo(PolydockAppInstance::class);
    }
}
