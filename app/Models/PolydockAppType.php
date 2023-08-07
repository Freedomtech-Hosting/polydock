<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolydockAppType extends Model
{
    use HasFactory;

    public function polydockAppInstances()
    {
        return $this->hasMany(PolydockAppInstance::class);
    }
}
