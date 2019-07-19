<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule;

class Module
{
    public function getConfig(): array
    {
        $config = (new ConfigProvider())();
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);

        return $config;
    }
}
