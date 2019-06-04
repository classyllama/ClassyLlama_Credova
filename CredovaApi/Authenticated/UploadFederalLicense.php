<?php
/**
 * @category    ClassyLlama
 * @package
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 */

namespace ClassyLlama\Credova\CredovaApi\Authenticated;


use ClassyLlama\Credova\CredovaApi\RequestAbstract;
use ClassyLlama\Credova\Exception\CredovaApiException;

class UploadFederalLicense extends \ClassyLlama\Credova\CredovaApi\Authenticated\AuthenticatedRequestAbstract
{

    const PATH = 'v2/federallicense/%s/uploadfile';

    protected $file_loc = null;

    /**
     * @return string
     * @throws CredovaApiException
     */
    protected function getPath(): string
    {
        if (!isset($this->getData()['license_number'])) {
//            throw new CredovaApiException(__('License number not set before license retrieval.'));
        }

        return sprintf(static::PATH, 'bb84a40a-dfc0-428c-924f-e60cd76a211e');
    }

    public function setFileLoc($fileLoc)
    {
            $this->file_loc = $fileLoc;
    }

    protected function getFileLength(){
        return $this->file_loc;
    }


    /**
     * Get request method
     *
     * @return string
     */
    protected
    function getMethod(): string
    {
        return \Zend\Http\Request::METHOD_POST;
    }

    /**
     * Add authentication header
     *
     * {@inheritdoc}
     * @throws \ClassyLlama\Credova\Exception\CredovaApiException
     */
    protected function getHeaders($contentLength = null) : array
    {
        $headers = parent::getHeaders();

        $authToken = $this->apiHelper->getAuthToken();

        $headers['Authorization'] = "Bearer $authToken";
        $headers['Content-Type'] = "multipart/form-data";
        if($contentLength){
            $headers['Content-Length'] = $contentLength;

        }
        print_r($contentLength . "CONTENT");

        return $headers;
    }

    protected function getContentLength(){
        return sizeof($this->getData());
    }

    /**
     * Make request and get response, Overridden from base to add custom headers and
     * allow for file uploads
     *
     * @return \Zend\Http\Response
     * @throws \ClassyLlama\Credova\Exception\CredovaApiException
     */
    public
    function getResponse(): \Zend\Http\Response
    {


        // Set log prefix which can be used to correlate request/response pairs
        // even if there are unrelated requests intermingled.
        $this->logPrefix = uniqid();

        /** @var \Zend\Http\Client $client */
        $client = $this->clientFactory->create(['timeout'=> 30]);

        $client->setUri($this->getUri());
        $client->setMethod($this->getMethod());




        if (!empty($this->getData())) {
            $requestBody = ($this->getData());
            print_r($requestBody);
            $client->setFileUpload($requestBody['file'], 'file');
        }
        $client->setHeaders($this->getHeaders(filesize($requestBody['file'])));

        $this->prepareRequest($client);
        $this->debugLog($client->getRequest()->toString());
        /** @var \Zend\Http\Response $response */
        $response = $client->send();
        if (!$response->isSuccess()) {
            var_dump($client);
            throw new CredovaApiException(__('Error on Credova API request'));
        }

        return $response;
    }

}