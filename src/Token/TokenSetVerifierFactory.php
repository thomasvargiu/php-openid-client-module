<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Token;

use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Token\IdTokenVerifierInterface;
use TMV\OpenIdClient\Token\TokenSetVerifier;

class TokenSetVerifierFactory
{
    public function __invoke(ContainerInterface $container): TokenSetVerifier
    {
        $idTokenVerifier = $container->get(IdTokenVerifierInterface::class);

        return new TokenSetVerifier($idTokenVerifier);
    }
}
