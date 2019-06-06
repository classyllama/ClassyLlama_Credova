<?php

namespace ClassyLlama\Credova\CredovaApi\Authenticated\Application;

class UploadInvoiceRequest extends \ClassyLlama\Credova\CredovaApi\Authenticated\Application\ApplicationRequestAbstract
{
    const PATH = 'v2/applications/{public_id}/uploadInvoice';

    /**
     * Name attribute for pdf file input
     */
    const FILE_INPUT_NAME = 'file';

    /**
     * File content type
     */
    const FILE_CONTENT_TYPE = 'application/pdf';

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $fileContent;

    /**
     * @param \Zend\Http\ClientFactory $clientFactory
     * @param \ClassyLlama\Credova\Helper\Config $configHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ClassyLlama\Credova\Helper\Api $apiHelper
     * @param string $publicId
     * @param string $fileName
     * @param string $fileContent
     */
    public function __construct(
        \Zend\Http\ClientFactory $clientFactory,
        \ClassyLlama\Credova\Helper\Config $configHelper,
        \Psr\Log\LoggerInterface $logger,
        \ClassyLlama\Credova\Helper\Api $apiHelper,
        string $publicId,
        string $fileName,
        string $fileContent
    ) {
        parent::__construct($clientFactory, $configHelper, $logger, $apiHelper, $publicId);
        $this->fileName = $fileName;
        $this->fileContent = $fileContent;
    }

    /**
     * @inheritdoc
     */
    protected function getMethod(): string
    {
        return \Zend\Http\Request::METHOD_POST;
    }

    /**
     * @inheritdoc
     */
    protected function getHeaders(): array
    {
        $headers = parent::getHeaders();

        /**
         * If request has file(s), 'Content-Type' MUST be empty
         * in order to force Zend HTTP client process files
         * and automatically set Content-Type:multipart/form-data header
         * @see \Zend\Http\Client::prepareBody
         */
        unset($headers['Content-Type']);

        return $headers;
    }

    /**
     * @inheritdoc
     */
    protected function prepareRequest(\Zend\Http\Client $client)
    {
        parent::prepareRequest($client);

        $client->setFileUpload(
            $this->fileName,
            static::FILE_INPUT_NAME,
            $this->fileContent,
            static::FILE_CONTENT_TYPE
        );
    }

    /**
     * @inheritdoc
     */
    protected function getData(): array
    {
        // make sure no json data will be set to body if parent has defaults
        return [];
    }
}
