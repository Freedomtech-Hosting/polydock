<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolydockAppInstanceDeployment extends Model
{
    use HasFactory;

    const DEPLOYMENT_STATUS_NEW = 'new';
    const DEPLOYMENT_STATUS_QUEUED = 'queued';
    const DEPLOYMENT_STATUS_PENDING = 'pending';
    const DEPLOYMENT_STATUS_RUNNING = 'running';
    const DEPLOYMENT_STATUS_CANCELLED = 'cancelled';
    const DEPLOYMENT_STATUS_FAILED = 'failed';
    const DEPLOYMENT_STATUS_COMPLETE = 'complete';

    protected $fillable = [
        'lagoon_name',
        'lagoon_remote_id',
        'lagoon_created_at',
        'lagoon_started_at',
        'lagoon_completed_at',
        'status',
    ];

    protected $casts = [
        'lagoon_created_at' => 'datetime',
        'lagoon_started_at' => 'datetime',
        'lagoon_completed_at' => 'datetime'
    ];

    public function polydockAppInstance()
    {
        return $this->belongsTo(PolydockAppInstance::class);
    }

    public static function inProgressStatuses() : array {
        return [
            self::DEPLOYMENT_STATUS_NEW,
            self::DEPLOYMENT_STATUS_QUEUED,
            self::DEPLOYMENT_STATUS_PENDING,
            self::DEPLOYMENT_STATUS_RUNNING,
        ];
    }
}
