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
        $requestBody = ($this->getData());
        return sprintf(static::PATH, $requestBody['public_id']); //TODO get this from DB
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
     * Sets header for file upload
     *
     * @param int|null $contentLength
     * @return array
     * @throws CredovaApiException
     */
    protected function getHeaders() : array
    {
        $headers = parent::getHeaders();

        $authToken = $this->apiHelper->getAuthToken();

        unset($headers['Content-Type']);
        $headers['Authorization'] = "Bearer $authToken";

        return $headers;
    }

    /**
     * Make request and get response
     * Overridden from parent class to allow headers and
     *  method calls needed for file uploads
     *
     * @return \Zend\Http\Response
     * @throws \ClassyLlama\Credova\Exception\CredovaApiException
     */
    public
    function getResponse(): \Zend\Http\Response
    {
        $requestBody = ($this->getData());

        // Set log prefix which can be used to correlate request/response pairs
        // even if there are unrelated requests intermingled.
        $this->logPrefix = uniqid();

        /** @var \Zend\Http\Client $client */
        $client = $this->clientFactory->create();
        $client->setUri($this->getUri());
        $client->setMethod($this->getMethod());
        $client->setHeaders($this->getHeaders());
        $client->setFileUpload($requestBody['file'] , 'file');
        $this->prepareRequest($client);
        /** @var \Zend\Http\Response $response */
        $response = $client->send();
        if (!$response->isSuccess()) {
            if( strpos( $response->getBody(), 'File')){
                throw new CredovaApiException(__($response->getBody()));
            };
        }
        return $response;
    }

}