<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Issuer;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use TMV\OpenIdClient\Issuer\IssuerFactoryInterface;
use TMV\OpenIdClient\Issuer\IssuerInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class IssuerAbstractFactory implements AbstractFactoryInterface
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
        if (! \preg_match('/^openid.issuers.([^.]+)$/', $requestedName, $matches)) {
            return false;
        }

        [, $name] = $matches;

        $config = $container->get('config')['openid']['issuers'][$name] ?? null;

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
     * @return IssuerInterface
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): IssuerInterface
    {
        if (! \preg_match('/^openid.issuers.([^.]+)$/', $requestedName, $matches)) {
            throw new ServiceNotFoundException('Unable to find a client named ' . $requestedName);
        }

        [, $name] = $matches;

        $config = $container->get('config')['openid']['issuers'][$name] ?? null;

        if (! \is_array($config)) {
            throw new ServiceNotFoundException('Unable to find a valid configuration for client named ' . $requestedName);
        }

        $discovery = $config['discovery'] ?? null;
        $webFinger = $config['webfinger'] ?? null;

        if ($discovery && $webFinger) {
            throw new ServiceNotCreatedException('Only one of "discovery" or "webfinger" config key should be provided');
        }

        if (! $discovery && ! $webFinger) {
            throw new ServiceNotCreatedException('No "discovery" or "webfinger" config key provided');
        }

        /** @var IssuerFactoryInterface $factory */
        $factory = $container->get($config['factory'] ?? IssuerFactoryInterface::class);

        return $discovery
            ? $factory->fromUri($discovery)
            : $factory->fromWebFinger($webFinger);
    }
}
