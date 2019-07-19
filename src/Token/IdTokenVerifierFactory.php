<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Token;

use Jose\Component\Signature\JWSVerifier;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Token\IdTokenVerifier;

class IdTokenVerifierFactory
{
    public function __invoke(ContainerInterface $container): IdTokenVerifier
    {
        $config = $container->get('config')['openid']['config'] ?? [];

        /** @var JWSVerifier $JWSVerifier */
        $JWSVerifier = $container->get('openid.service.jws_verifier');

        $clockTolerance = (int) ($config['clock_tolerance'] ?? 0);
        $aadIssValidation = (bool) ($config['aad_iss_validation'] ?? false);

        return new IdTokenVerifier(
            $JWSVerifier,
            $aadIssValidation,
            $clockTolerance
        );
    }
}
