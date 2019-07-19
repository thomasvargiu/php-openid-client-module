<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Claims;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializer;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TMV\OpenIdClient\Claims\DistributedParser;
use TMV\OpenIdClient\Claims\DistributedParserInterface;
use TMV\OpenIdClient\Issuer\IssuerFactoryInterface;

class DistributedParserFactory
{
    public function __invoke(ContainerInterface $container): DistributedParserInterface
    {
        /** @var ClientInterface $httpClient */
        $httpClient = $container->get('openid.service.http_client');

        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_factory');

        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');

        /** @var JWSVerifier $JWSVerifier */
        $JWSVerifier = $container->get('openid.service.jws_verifier');

        /** @var JWSSerializer $JWSSerializer */
        $JWSSerializer = $container->get('openid.service.jws_serializer');

        /** @var IssuerFactoryInterface $issuerFactory */
        $issuerFactory = $container->get(IssuerFactoryInterface::class);

        return new DistributedParser(
            $httpClient,
            $requestFactory,
            $algorithmManager,
            $JWSVerifier,
            $issuerFactory
        );
    }
}
