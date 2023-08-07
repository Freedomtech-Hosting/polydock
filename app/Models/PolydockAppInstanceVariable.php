<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolydockAppInstanceVariable extends Model
{
    use HasFactory;

    public $fillable = [
        'key',
        'value'
    ];

    public function polydockAppInstance()
    {
        return $this->belongsTo(PolydockAppInstance::class);
    }
}
