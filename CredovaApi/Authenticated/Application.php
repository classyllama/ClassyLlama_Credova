<?php


namespace ClassyLlama\Credova\CredovaApi\Authenticated;


use ClassyLlama\Credova\Api\Data\ApplicationInfoInterface;

class Application extends AuthenticatedRequestAbstract
{
    const PATH = 'v2/applications/';

    /**
     * @var array
     */
    protected $data;

    public function __construct(\Zend\Http\ClientFactory $clientFactory, \ClassyLlama\Credova\Helper\Config $configHelper, \Psr\Log\LoggerInterface $logger, \ClassyLlama\Credova\Helper\Api $apiHelper, array $applicationInfo = [])
    {
        parent::__construct($clientFactory, $configHelper, $logger, $apiHelper);
        $this->data = $applicationInfo;
    }

    /**
     * Get request path
     *
     * @return string
     */
    protected function getPath(): string
    {
        return static::PATH;
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

    public function getData(): array
    {
        return $this->data;
    }
}
