<?php

namespace ClassyLlama\Credova\CredovaApi;

use ClassyLlama\Credova\Exception\CredovaApiException;

abstract class RequestAbstract
{
    const CONTENT_TYPE = 'application/json';

    /**
     * @var \Zend\Http\ClientFactory
     */
    protected $clientFactory;
    /**
     * @var \ClassyLlama\Credova\Helper\Config
     */
    protected $configHelper;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * @var string
     */
    protected $logPrefix;
    /**
     * @var array
     */
    protected $data = [];

    /**
     * RequestAbstract constructor.
     * @param \Zend\Http\ClientFactory $clientFactory
     * @param \ClassyLlama\Credova\Helper\Config $configHelper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Zend\Http\ClientFactory $clientFactory,
        \ClassyLlama\Credova\Helper\Config $configHelper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->clientFactory = $clientFactory->create();
        $this->clientFactory = $clientFactory;
        $this->configHelper = $configHelper;
        $this->logger = $logger;
    }

    /**
     * Get request path
     *
     * @return string
     */
    abstract protected function getPath() : string;

    /**
     * Get request method
     *
     * @return string
     */
    abstract protected function getMethod() : string;

    /**
     * Assemble full URI, taking care of any leading or trailing slashes
     *
     * @return string
     */
    protected function getUri() : string
    {
        $host = rtrim($this->configHelper->getCredovaApiUrl(), '/');

        $path = ltrim($this->getPath());

        return $host . '/' . $path;
    }

    /**
     * Get request data, if applicable
     *
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }

    /**
     * Set request data, if applicable
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Perform any request preparation prior to getting response
     *
     * @param \Zend\Http\Client $client
     */
    protected function prepareRequest(\Zend\Http\Client $client)
    {
        // This page intentionally left blank.
    }

    /**
     * Get request headers array
     *
     * @return array
     */
    protected function getHeaders() : array
    {
        $headers = [
            'Content-Type' => static::CONTENT_TYPE
        ];

        return $headers;
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
            throw new CredovaApiException(__('Error on Credova API request'));
        }

        return $response;
    }

    /**
     * Get decoded response data
     *
     * @return array
     * @throws \ClassyLlama\Credova\Exception\CredovaApiException
     */
    public function getResponseData() : array
    {
        $response = $this->getResponse();

        $data = json_decode($response->getBody(), true);

        if (is_null($data) && json_last_error() !== JSON_ERROR_NONE) {
            throw new CredovaApiException(__('Error decoding Credova response body: %1', json_last_error()));
        }

        return $data;
    }

    /**
     * If enabled, log debug info.
     *
     * @param string $message
     */
    protected function debugLog(string $message)
    {
        if (!$this->configHelper->getCredovaLoggingEnabled()) {
            return;
        }

        $message = $this->logPrefix . " - " . $message;

        $this->logger->debug($message);
    }
}
