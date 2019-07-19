<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Claims;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializer;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Claims\AggregatedParserInterface;
use TMV\OpenIdClient\Claims\AggregateParser;
use TMV\OpenIdClient\Issuer\IssuerFactoryInterface;

class AggregateParserFactory
{
    public function __invoke(ContainerInterface $container): AggregatedParserInterface
    {
        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');

        /** @var JWSVerifier $JWSVerifier */
        $JWSVerifier = $container->get('openid.service.jws_verifier');

        /** @var JWSSerializer $JWSSerializer */
        $JWSSerializer = $container->get('openid.service.jws_serializer');

        /** @var IssuerFactoryInterface $issuerFactory */
        $issuerFactory = $container->get(IssuerFactoryInterface::class);

        return new AggregateParser(
            $algorithmManager,
            $JWSVerifier,
            $issuerFactory,
            $JWSSerializer
        );
    }
}
