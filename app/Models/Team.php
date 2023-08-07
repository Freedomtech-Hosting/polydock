<?php

namespace App\Models;

use App\Exceptions\TeamLagoonSshPrivKeyRequired;
use App\Lagoon\Client\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;

    const CACHE_LAGOON_TEAM_TOKEN_MINUTES = 15;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'personal_team' => 'boolean',
        'lagoon_token_expires' => 'datetime'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function polydockAppInstances()
    {
        return $this->hasMany(PolydockAppInstance::class);
    }

    public function polydockAppInstanceDeployments()
    {
        return $this->hasManyThrough(PolydockAppInstanceDeployment::class, PolydockAppInstance::class);
    }

    public function polydockAppInstanceDeploymentsInProgress()
    {
        return $this->hasManyThrough(PolydockAppInstanceDeployment::class, PolydockAppInstance::class)
            ->whereIn('polydock_app_instance_deployments.status', PolydockAppInstanceDeployment::inProgressStatuses());
    }

    public function getUnencryptedLagoonSshPrivateKey()
    {
        return $this->lagoon_ssh_priv_key;
    }

    public function getLagoonClient($getToken = true, $forceRefreshToken = false)
    {
        $key = $this->getUnencryptedLagoonSshPrivateKey();

        if(!$key) {
            throw new TeamLagoonSshPrivKeyRequired("Team ssh private key is missing");
        }

        $client = new Client($key);

        if($getToken || $forceRefreshToken) {
            if($this->lagoon_token && ! $forceRefreshToken && Carbon::now() < $this->lagoon_token_expires) {
                $client->setLagoonToken($this->lagoon_token);
            } else {
                $client->getLagoonTokenOverSSH();
                $this->lagoon_token = $client->getLagoonToken(true);
                $this->lagoon_token_expires = Carbon::now()->addMinutes(self::CACHE_LAGOON_TEAM_TOKEN_MINUTES);
                $this->save();
            }
        }

        return $client;
    }
}
