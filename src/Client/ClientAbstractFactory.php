<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Client;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Jose\Component\Core\JWKSet;
use TMV\OpenIdClient\AuthMethod\AuthMethodFactory;
use TMV\OpenIdClient\AuthMethod\AuthMethodFactoryInterface;
use TMV\OpenIdClient\Client\Client;
use TMV\OpenIdClient\Client\ClientInterface;
use TMV\OpenIdClient\Client\Metadata\ClientMetadata;
use TMV\OpenIdClient\Client\Metadata\ClientMetadataInterface;
use TMV\OpenIdClient\Issuer\IssuerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class ClientAbstractFactory implements AbstractFactoryInterface
{

    /**
     * Can the factory create an instance for the service?
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        if (! \preg_match('/^openid.clients.([^.]+)$/', $requestedName, $matches)) {
            return false;
        }

        [, $name] = $matches;

        $config = $container->get('config')['openid']['clients'][$name] ?? null;

        if (! \is_array($config)) {
            return false;
        }

        return true;
    }

    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return ClientInterface
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): ClientInterface
    {
        if (! \preg_match('/^openid.clients.([^.]+)$/', $requestedName, $matches)) {
            throw new ServiceNotFoundException('Unable to find a client named ' . $requestedName);
        }

        [, $name] = $matches;

        $config = $container->get('config')['openid']['clients'][$name] ?? null;

        if (! \is_array($config)) {
            throw new ServiceNotFoundException('Unable to find a valid configuration for client named ' . $requestedName);
        }

        $jwks = $config['jwks'] ?? null;
        $authMethods = $config['auth_methods'] ?? null;

        /** @var AuthMethodFactoryInterface $authMethodFactory */
        $authMethodFactory = ! \is_array($authMethods)
            ? $container->get(AuthMethodFactoryInterface::class)
            : new AuthMethodFactory(\array_map([$container, 'get'], $authMethods));

        $httpClientName = $config['http_client'] ?? null;

        $httpClient = $httpClientName ? $container->get($httpClientName) : null;

        return new Client(
            $this->getIssuer($container, $requestedName, $config['issuer'] ?? null),
            $this->getMetadata($container, $requestedName, $config['metadata'] ?? null),
            $jwks ? $this->getJWKSet($container, $requestedName, $jwks) : new JWKSet([]),
            $authMethodFactory,
            $httpClient
        );
    }

    private function getIssuer(ContainerInterface $container, string $requestedName, $issuer): IssuerInterface
    {
        if (! \is_string($issuer)) {
            throw new ServiceNotCreatedException('Invalid issuer provided for client named ' . $requestedName);
        }

        $issuer = $container->get($issuer);

        if (! $issuer instanceof IssuerInterface) {
            throw new ServiceNotCreatedException('Invalid issuer provided for client named ' . $requestedName);
        }

        return $issuer;
    }

    private function getMetadata(ContainerInterface $container, string $requestedName, $metadata): ClientMetadataInterface
    {
        if (! \is_array($metadata)) {
            throw new ServiceNotCreatedException('Invalid metadata provided for client named ' . $requestedName);
        }

        return ClientMetadata::fromArray($metadata);
    }

    private function getJWKSet(ContainerInterface $container, string $requestedName, $jwks): JWKSet
    {
        if (\is_array($jwks)) {
            return JWKSet::createFromKeyData($jwks);
        }

        if (! \is_string($jwks)) {
            throw new ServiceNotCreatedException('Invalid jwks provided for client named ' . $requestedName);
        }

        $decoded = \json_decode($jwks, true);

        if (\is_array($decoded)) {
            return JWKSet::createFromKeyData($decoded);
        }

        $jwkSet = $container->get($jwks);

        if (! $jwkSet instanceof JWKSet) {
            throw new ServiceNotCreatedException('Invalid jwks provided for client named ' . $requestedName);
        }

        return $jwkSet;
    }
}
