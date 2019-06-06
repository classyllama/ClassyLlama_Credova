<?php

namespace ClassyLlama\Credova\CredovaApi\Authenticated\Application;

abstract class ApplicationRequestAbstract extends \ClassyLlama\Credova\CredovaApi\Authenticated\AuthenticatedRequestAbstract
{
    /**
     * Base path for application requests.
     * Should be overridden in specific request implementations
     */
    const PATH = 'v2/applications/{public_id}/';

    /**
     * @var string
     */
    protected $publicId;

    /**
     * @param \Zend\Http\ClientFactory $clientFactory
     * @param \ClassyLlama\Credova\Helper\Config $configHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \ClassyLlama\Credova\Helper\Api $apiHelper
     * @param string $publicId
     */
    public function __construct(
        \Zend\Http\ClientFactory $clientFactory,
        \ClassyLlama\Credova\Helper\Config $configHelper,
        \Psr\Log\LoggerInterface $logger,
        \ClassyLlama\Credova\Helper\Api $apiHelper,
        string $publicId
    ) {
        parent::__construct($clientFactory, $configHelper, $logger, $apiHelper);
        $this->publicId = $publicId;
    }

    /**
     * @inheritdoc
     */
    protected function getPath(): string
    {
        return str_replace('{public_id}', $this->publicId, static::PATH);
    }
}
