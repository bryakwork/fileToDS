<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 19:50
 */

namespace rollun\file2ds;


use rollun\actionrender\Installers\ActionRenderInstaller;
use rollun\actionrender\Installers\BasicRenderInstaller;
use rollun\datastore\DataStore\Installers\CacheableInstaller;
use rollun\datastore\DataStore\Installers\CsvInstaller;
use rollun\datastore\DataStore\Installers\MemoryInstaller;
use rollun\datastore\Middleware\ResourceResolver;
use rollun\file2ds\Middleware\Factory\File2DSMiddlewarefactory;
use rollun\file2ds\Middleware\File2DSMiddleware;
use rollun\file2ds\Middleware\File2DSRequestDecoder;
use rollun\installer\Install\InstallerAbstract;

class File2DSInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        return [
            'action_render_service' => [
                'file2DS' => [
                    'action_middleware_service' => 'file2DSAction',
                    'render_middleware_service' => 'dataStoreHtmlJsonRendererLLPipe',
                ],
            ],
            'middleware_pipe_abstract' => [
                'file2DSAction' => [
                    'middlewares' => [
                        ResourceResolver::class,
                        File2DSRequestDecoder::class,
                        'file2dsLLPipe',
                    ],
                ],
            ],
            'LazyLoadPipe' => [
                'file2dsLLPipe' => LazyLoadFile2DSMiddlewareGetter::class,
                'dataStoreHtmlJsonRendererLLPipe' => 'dataStoreHtmlJsonRenderer',
            ],
            'dependencies' => [
                'factories' => [
                    File2DSMiddleware::class => File2DSMiddlewarefactory::class,
                ],
                'invokables' => [
                    ResourceResolver::class => ResourceResolver::class,
                    File2DSRequestDecoder::class => File2DSRequestDecoder::class,
                    LazyLoadFile2DSMiddlewareGetter::class => LazyLoadFile2DSMiddlewareGetter::class,
                ],
            ],
        ];
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    /**
     * Return true if install, or false else
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function isInstall()
    {
        $config = $this->container->get('config');
        return (
            isset($config['action_render_service']['file2DS']) &&
            isset($config['middleware_pipe_abstract']['file2DSAction']['middlewares']) &&
            isset($config['LazyLoadPipe']['file2dsLLPipe']) &&
            isset($config['LazyLoadPipe']['dataStoreHtmlJsonRendererLLPipe']) &&
            isset($config['dependencies']['factories'][File2DSMiddleware::class]) &&
            isset($config['dependencies']['invokables'][ResourceResolver::class]) &&
            isset($config['dependencies']['invokables'][File2DSRequestDecoder::class]) &&
            isset($config['dependencies']['invokables'][LazyLoadFile2DSMiddlewareGetter::class])
        );
    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Модуль для загрузки данных из файла в DataStore";
                break;
            case "en":
                $description = "Module for writing data from file to datastore";
                break;
            default:
                $description = "No description";
        }
        return $description;
    }

    public function getDependencyInstallers()
    {
        return [
            CsvInstaller::class,
            MemoryInstaller::class,
            ActionRenderInstaller::class,
            BasicRenderInstaller::class,
            CacheableInstaller::class,
        ];
    }
}