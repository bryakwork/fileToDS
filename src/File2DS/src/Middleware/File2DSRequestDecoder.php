<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 18:54
 */

namespace rollun\file2ds\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class File2DSRequestDecoder implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $delimeter = $request->getParsedBody()['delimeter'];
        /** var Zend\Diactoros\UploadedFile $fileObject */
        $fileObject = ($request->getUploadedFiles())['file'];
        $request = $request->withAttribute('file2DSDelimeter', $delimeter)
                            ->withAttribute('uploadedFile', $fileObject);
        $response = $delegate->process($request);
        return $response;
    }
}