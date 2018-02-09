<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 16:10
 */

namespace rollun\file2ds\Middleware\Factory;


use Interop\Container\ContainerInterface;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use rollun\file2ds\File2DSException;
use rollun\file2ds\Middleware\File2DSMiddleware;
use Zend\ServiceManager\Factory\FactoryInterface;

class File2DSMiddlewarefactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName Name of DataStore that will store the information from the file
     * @param  null|array $options
     * @return object
     * @throws File2DSException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $resourceName = $requestedName;
        if (!$container->has($resourceName)) {
            throw new File2DSException(
                "Resource '$resourceName' was not found"
            );
        }
        $dataStore = $container->get($resourceName);
        if (!is_a($dataStore, DataStoresInterface::class, true))
        {
            throw new File2DSException("Resource '$resourceName' is not a DataStore");
        }
        $file2ds = new File2DSMiddleware($dataStore);
        return $file2ds;
    }
}