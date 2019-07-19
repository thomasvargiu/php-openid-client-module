<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializer;
use Psr\Container\ContainerInterface;

class JWSSerializerFactory
{
    public function __invoke(ContainerInterface $container): JWSSerializer
    {
        return new CompactSerializer();
    }
}
