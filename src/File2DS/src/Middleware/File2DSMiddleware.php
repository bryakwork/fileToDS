<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 13:04
 */

namespace rollun\file2ds\Middleware;


use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use rollun\datastore\DataStore\Cacheable;
use rollun\datastore\DataStore\CsvBase;
use rollun\datastore\DataStore\Interfaces\DataStoresInterface;
use rollun\installer\Command;
use Symfony\Component\Filesystem\LockHandler;
use Zend\Diactoros\Response\EmptyResponse;

class File2DSMiddleware implements MiddlewareInterface
{
    private $dataStore;
    private $tmpDirName;

    public function __construct(DataStoresInterface $dataStore,  $tmpDirName = null)
    {
        $this->dataStore = $dataStore;
        if (isset($delimeter)) {
            $this->tmpDirName = $tmpDirName;
        } else $this->tmpDirName = Command::getDataDir();
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return Response
     * @throws \rollun\datastore\DataStore\DataStoreException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        /** var Zend\Diactoros\UploadedFile $fileObject */
        $file = $request->getAttribute('uploadedFile');
        $delimeter = $request->getAttribute('file2DSDelimeter');
        $this->storeData($file, $delimeter);
        $request = $request->withAttribute(Response::class, new EmptyResponse(200))
                            ->withAttribute('responseData', 'Data uploaded successfully');
        $response = $delegate->process($request);
        return $response;
    }

    /**
     * Write file contents to DataStore, then delete the file
     * @param \Zend\Diactoros\UploadedFile $file
     * @param string $delimeter
     * @throws \rollun\datastore\DataStore\DataStoreException
     */
    protected function storeData($file, $delimeter)
    {
        $fileName = $file->getClientFilename();
        $filePath = $this->tmpDirName . '/' . $fileName;
        $file->moveTo($filePath);
        $dataSource = new CsvBase($filePath, $delimeter, new LockHandler($filePath));
        $resultStore = new Cacheable($dataSource, $this->dataStore);
        $resultStore->refresh();
        unlink($filePath);
    }
}