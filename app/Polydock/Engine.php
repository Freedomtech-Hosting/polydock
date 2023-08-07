<?php namespace App\Polydock;

class Engine {
    protected $appTypeRegistry = [];

    public function registerAppType($type, AppEngineInterface $engine) {
        $this->appTypeRegistry[$type] = $engine;
    }

    public function getRegisteredAppTypes()
    {
        return $this->appTypeRegistry;
    }

    public function getAppEngine($type) : AppEngineInterface
    {
        return $this->appTypeRegistry[$type] ?? null;
    }
}
