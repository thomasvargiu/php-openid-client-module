<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Encryption\Serializer\CompactSerializer;
use Jose\Component\Encryption\Serializer\JWESerializer;
use Psr\Container\ContainerInterface;

class JWESerializerFactory
{
    public function __invoke(ContainerInterface $container): JWESerializer
    {
        return new CompactSerializer();
    }
}
