<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolydockAppInstance extends Model
{
    use HasFactory;

    protected $casts = [
        'lagoon_project_json' => 'array',
        'lagoon_environment_json' => 'array',
        'lagoon_routes_json' => 'array'
    ];

    protected $fillable = [
        'name',
        'description',
        'polydock_lagoon_cluster_id',
        'polydock_app_type_id',
    ];


    const STATUS_PRE_CREATE = "pre-creating";
    const STATUS_CREATE = "creating";
    const STATUS_POST_CREATE = "post-creating";
    const STATUS_CREATED = "created";

    const STATUS_PRE_DEPLOY = "pre-deploying";
    const STATUS_DEPLOY = "deploying";
    const STATUS_POST_DEPLOY = "post-deploying";

    const STATUS_RUNNING = "running";

    const STATUS_FAILED = "failed";

    const STATUS_PRE_REMOVE = "pre-remove";
    const STATUS_REMOVE = "removing";
    const STATUS_POST_REMOVE = "post-remove";

    const STATUS_REMOVED = "removed";

    public function saveStatus(string $status)
    {
        $this->logDebug("status => " . $status);
        $this->status = $status;
        return $this->save();
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function polydockAppType()
    {
        return $this->belongsTo(PolydockAppType::class);
    }

    public function polydockLagoonCluster()
    {
        return $this->belongsTo(PolydockLagoonCluster::class);
    }

    public function deployments()
    {
        return $this->hasMany(PolydockAppInstanceDeployment::class);
    }

    public function logs()
    {
        return $this->hasMany(PolydockAppInstanceLog::class);
    }

    public function variables()
    {
        return $this->hasMany(PolydockAppInstanceVariable::class);
    }

    public function setVariableValue($key, $value)
    {
        $var = $this->variables()->where('key', $key)->first();
        if($var) {
            $var->value = $value;
            $var->save();
        } else {
            $this->variables()->create([
                'key' => $key,
                'value' => $value
            ]);
        }
    }

    public function getVariableValue($key)
    {
        $var = $this->variables()->where('key', $key)->first();
        if($var) {
            return $var->value;
        }
    }

    public function logLine($level, $log, array $data = [])
    {
        $this->logs()->create([
            'level' => $level,
            'log' => $log,
            'data' => $data
        ]);
    }

    public function logInfo($log, array $data = [])
    {
        return $this->logLine(PolydockAppInstanceLog::LOG_LEVEL_INFO, $log, $data);
    }

    public function logDebug($log, array $data = [])
    {
        return $this->logLine(PolydockAppInstanceLog::LOG_LEVEL_DEBUG, $log, $data);
    }

    public function logWarn($log, array $data = [])
    {
        return $this->logLine(PolydockAppInstanceLog::LOG_LEVEL_WARN, $log, $data);
    }

    public function logError($log, array $data = [])
    {
        return $this->logLine(PolydockAppInstanceLog::LOG_LEVEL_ERROR, $log, $data);
    }
}
