<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule;

use Jose\Component\KeyManagement\JKUFactory;
use Jose\Component\Signature\Algorithm\PS256;
use Jose\Component\Signature\Algorithm\PS384;
use Jose\Component\Signature\Algorithm\PS512;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\Algorithm\RS384;
use Jose\Component\Signature\Algorithm\RS512;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TMV\OpenIdClient\AuthMethod;
use TMV\OpenIdClient\Authorization;
use TMV\OpenIdClient\Claims;
use TMV\OpenIdClient\Client;
use TMV\OpenIdClient\Issuer;
use TMV\OpenIdClient\Middleware;
use TMV\OpenIdClient\RequestObject;
use TMV\OpenIdClient\Service;
use TMV\OpenIdClient\Session;
use TMV\OpenIdClient\Token;
use TMV\OpenIdClientModule\AuthMethod\AuthMethodFactoryFactory;
use TMV\OpenIdClientModule\AuthMethod\ClientSecretJwtFactory;
use TMV\OpenIdClientModule\AuthMethod\PrivateKeyJwtFactory;
use TMV\OpenIdClientModule\Claims\AggregateParserFactory;
use TMV\OpenIdClientModule\Claims\DistributedParserFactory;
use TMV\OpenIdClientModule\Client\ClientAbstractFactory;
use TMV\OpenIdClientModule\Issuer\IssuerAbstractFactory;
use TMV\OpenIdClientModule\Issuer\IssuerFactoryFactory;
use TMV\OpenIdClientModule\Issuer\Metadata\MetadataFactoryFactory;
use TMV\OpenIdClientModule\Issuer\Metadata\Provider\DiscoveryProviderFactory;
use TMV\OpenIdClientModule\Issuer\Metadata\Provider\WebFingerProviderFactory;
use TMV\OpenIdClientModule\Middleware\AuthRedirectHandlerFactory;
use TMV\OpenIdClientModule\Middleware\UserInfoMiddlewareFactory;
use TMV\OpenIdClientModule\RequestObject\RequestObjectFactoryFactory;
use TMV\OpenIdClientModule\Service\AuthorizationServiceFactory;
use TMV\OpenIdClientModule\Service\IntrospectionServiceFactory;
use TMV\OpenIdClientModule\Service\RegistrationServiceFactory;
use TMV\OpenIdClientModule\Service\RevocationServiceFactory;
use TMV\OpenIdClientModule\Service\UserinfoServiceFactory;
use TMV\OpenIdClientModule\Token\IdTokenVerifierFactory;
use TMV\OpenIdClientModule\Token\ResponseTokenVerifierFactory;
use TMV\OpenIdClientModule\Token\TokenDecrypterFactory;
use TMV\OpenIdClientModule\Token\TokenSetVerifierFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
            'openid' => [
                'config' => [
                    'aad_iss_validation' => false,
                    'clock_tolerance' => 0,
                ],
                'algorithms' => [
                    RS256::class,
                    RS384::class,
                    RS512::class,
                    PS256::class,
                    PS384::class,
                    PS512::class,
                ],
                'auth_methods' => [
                    'client_secret_basic' => AuthMethod\ClientSecretBasic::class,
                    'client_secret_jwt' => AuthMethod\ClientSecretJwt::class,
                    'client_secret_post' => AuthMethod\ClientSecretPost::class,
                    'none' => AuthMethod\None::class,
                    'private_key_jwt' => AuthMethod\PrivateKeyJwt::class,
                    'self_signed_tls_client_auth' => AuthMethod\SelfSignedTLSClientAuth::class,
                    'tls_client_auth' => AuthMethod\TLSClientAuth::class,
                ],
                'factories' => [
                    'openid.service.jku_factory' => JKUFactory::class,
                    'openid.factory.issuer_factory' => Issuer\IssuerFactoryInterface::class,
                    'openid.factory.auth_method_factory' => '',
                    'openid.factory.algorithm_manager_factory' => '',
                ],
                'issuers' => [
                    'default' => [
                        'discovery' => '',
                        'webfinger' => '',
                        'factory' => 'openid.factory.issuer_factory',
                    ],
                ],
                'clients' => [
                    'default' => [
                        'issuer' => 'openid.issuers.default',
                        'metadata' => [],
                        'jwks' => [],
                        'auth_methods' => [],
                    ],
                ],
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'abstract_factories' => [
                ClientAbstractFactory::class,
                IssuerAbstractFactory::class,
            ],
            'aliases' => [
                AuthMethod\AuthMethodFactoryInterface::class => AuthMethod\AuthMethodFactory::class,
                Authorization\AuthRequestInterface::class => Authorization\AuthRequest::class,
                Claims\AggregatedParserInterface::class => Claims\AggregateParser::class,
                Claims\DistributedParserInterface::class => Claims\DistributedParser::class,
                Client\Metadata\MetadataFactoryInterface::class => Client\Metadata\MetadataFactory::class,
                Client\Metadata\ClientMetadataInterface::class => Client\Metadata\ClientMetadata::class,
                Issuer\Metadata\Provider\DiscoveryProviderInterface::class => Issuer\Metadata\Provider\DiscoveryProvider::class,
                Issuer\Metadata\Provider\WebFingerProviderInterface::class => Issuer\Metadata\Provider\WebFingerProvider::class,
                Issuer\Metadata\IssuerMetadataInterface::class => Issuer\Metadata\IssuerMetadata::class,
                Issuer\Metadata\MetadataFactoryInterface::class => Issuer\Metadata\MetadataFactory::class,
                Issuer\IssuerFactoryInterface::class => Issuer\IssuerFactory::class,
                Session\AuthSessionInterface::class => Session\AuthSession::class,
                Token\IdTokenVerifierInterface::class => Token\IdTokenVerifier::class,
                Token\ResponseTokenVerifierInterface::class => Token\ResponseTokenVerifier::class,
                Token\TokenDecrypterInterface::class => Token\TokenDecrypter::class,
                Token\TokenSetInterface::class => Token\TokenSet::class,
                Token\TokenSetFactoryInterface::class => Token\TokenSetFactory::class,
                Token\TokenSetVerifierInterface::class => Token\TokenSetVerifier::class,
                'openid.factory.request_factory' => RequestFactoryInterface::class,
                'openid.factory.response_factory' => RequestFactoryInterface::class,
                'openid.factory.uri_factory' => UriFactoryInterface::class,
                'openid.service.http_client' => ClientInterface::class,
                'openid.service.jku_factory' => JWTFramework\JKUFactory::class,
                'openid.service.algorithm_manager' => JWTFramework\AlgorithmManager::class,
                'openid.service.jws_builder' => JWTFramework\JWSBuilder::class,
                'openid.service.jws_serializer' => JWTFramework\JWSSerializer::class,
                'openid.service.jwe_builder' => JWTFramework\JWEBuilder::class,
                'openid.service.jwe_serializer' => JWTFramework\JWESerializer::class,
                'openid.service.jws_verifier' => JWTFramework\JWSVerifier::class,
                'openid.service.jwe_loader' => JWTFramework\JWELoader::class,
            ],
            'factories' => [
                JWTFramework\AlgorithmManager::class => JWTFramework\AlgorithmManagerFactory::class,
                JWTFramework\JWSBuilder::class => JWTFramework\JWSBuilderFactory::class,
                JWTFramework\JWSSerializer::class => JWTFramework\JWSSerializerFactory::class,
                JWTFramework\JWEBuilder::class => JWTFramework\JWEBuilderFactory::class,
                JWTFramework\JWESerializer::class => JWTFramework\JWESerializerFactory::class,
                JWTFramework\JWSVerifier::class => JWTFramework\JWSVerifierFactory::class,
                JWTFramework\JWELoader::class => JWTFramework\JWSVerifierFactory::class,
                JWTFramework\JKUFactory::class => JWTFramework\JWELoaderFactory::class,
                AuthMethod\AuthMethodFactory::class => AuthMethodFactoryFactory::class,
                AuthMethod\ClientSecretBasic::class => InvokableFactory::class,
                AuthMethod\ClientSecretJwt::class => ClientSecretJwtFactory::class,
                AuthMethod\ClientSecretPost::class => InvokableFactory::class,
                AuthMethod\None::class => InvokableFactory::class,
                AuthMethod\PrivateKeyJwt::class => PrivateKeyJwtFactory::class,
                AuthMethod\SelfSignedTLSClientAuth::class => InvokableFactory::class,
                AuthMethod\TLSClientAuth::class => InvokableFactory::class,

                Claims\AggregateParser::class => AggregateParserFactory::class,
                Claims\DistributedParser::class => DistributedParserFactory::class,

                Client\Metadata\MetadataFactory::class => InvokableFactory::class,
                //Client\Metadata\ClientMetadata::class,

                Issuer\Metadata\Provider\DiscoveryProvider::class => DiscoveryProviderFactory::class,
                Issuer\Metadata\Provider\WebFingerProvider::class => WebFingerProviderFactory::class,
                //Issuer\Metadata\IssuerMetadata::class,
                Issuer\Metadata\MetadataFactory::class => MetadataFactoryFactory::class,
                Issuer\IssuerFactory::class => IssuerFactoryFactory::class,

                Middleware\AuthRedirectHandler::class => AuthRedirectHandlerFactory::class,
                //Middleware\AuthRequestProviderMiddleware::class,
                Middleware\CallbackMiddleware::class => Middleware\CallbackMiddleware::class,
                //Middleware\ClientProviderMiddleware::class,
                Middleware\SessionCookieMiddleware::class => InvokableFactory::class,
                Middleware\UserInfoMiddleware::class => UserInfoMiddlewareFactory::class,

                RequestObject\RequestObjectFactory::class => RequestObjectFactoryFactory::class,

                Service\AuthorizationService::class => AuthorizationServiceFactory::class,
                Service\IntrospectionService::class => IntrospectionServiceFactory::class,
                Service\RegistrationService::class => RegistrationServiceFactory::class,
                Service\RevocationService::class => RevocationServiceFactory::class,
                Service\UserinfoService::class => UserinfoServiceFactory::class,

                Token\IdTokenVerifier::class => IdTokenVerifierFactory::class,
                Token\ResponseTokenVerifier::class => ResponseTokenVerifierFactory::class,
                Token\TokenDecrypter::class => TokenDecrypterFactory::class,
                Token\TokenSetVerifier::class => TokenSetVerifierFactory::class,
                Token\TokenSetFactory::class => InvokableFactory::class,
            ],
        ];
    }
}
