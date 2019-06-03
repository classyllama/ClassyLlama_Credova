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

    /**
     * @return string
     * @throws CredovaApiException
     */
    protected function getPath(): string
    {
        if (!isset($this->getData()['license_number'])) {
//            throw new CredovaApiException(__('License number not set before license retrieval.'));
        }

        return sprintf(static::PATH,'9512885139');
    }

    /**
     * Get request method
     *
     * @return string
     */
    protected function getMethod(): string
    {
        return \Zend\Http\Request::METHOD_POST;
    }
    /**
     * Make request and get response
     *
     * @return \Zend\Http\Response
     * @throws \ClassyLlama\Credova\Exception\CredovaApiException
     */
    public function getResponse() : \Zend\Http\Response
    {
        // Set log prefix which can be used to correlate request/response pairs
        // even if there are unrelated requests intermingled.
        $this->logPrefix = uniqid();

        /** @var \Zend\Http\Client $client */
        $client = $this->clientFactory->create();

        $client->setUri($this->getUri());
        $client->setMethod($this->getMethod());
        $client->setHeaders($this->getHeaders());

        if (!empty($this->getData())) {
            $requestBody = json_encode($this->getData());

            $this->debugLog($requestBody);

            $client->setRawBody($requestBody);
        }

        $this->prepareRequest($client);

        $this->debugLog($client->getRequest()->toString());

        /** @var \Zend\Http\Response $response */
        $response = $client->send();

        $this->debugLog(
            $response->getStatusCode() . "\n" .
            $response->getHeaders()->toString() . "\n" .
            $response->getBody()
        );

        if (!$response->isSuccess()) {
            var_dump($client);
            throw new CredovaApiException(__('Same old same old'));
        }

        return $response;
    }

}