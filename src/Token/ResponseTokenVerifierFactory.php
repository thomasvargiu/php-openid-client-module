<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Token;

use Jose\Component\Signature\JWSVerifier;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Token\ResponseTokenVerifier;

class ResponseTokenVerifierFactory
{
    public function __invoke(ContainerInterface $container): ResponseTokenVerifier
    {
        $config = $container->get('config')['openid']['config'] ?? [];

        /** @var JWSVerifier $JWSVerifier */
        $JWSVerifier = $container->get('openid.service.jws_verifier');

        $aadIssValidation = (bool) ($config['aad_iss_validation'] ?? false);
        $clockTolerance = (int) ($config['clock_tolerance'] ?? 0);

        return new ResponseTokenVerifier(
            $JWSVerifier,
            $aadIssValidation,
            $clockTolerance
        );
    }
}
