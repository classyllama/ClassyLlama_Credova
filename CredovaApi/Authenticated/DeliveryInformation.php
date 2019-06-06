<?php


namespace ClassyLlama\Credova\CredovaApi\Authenticated;


use ClassyLlama\Credova\Api\Data\ApplicationInfoInterface;

class DeliveryInformation extends AuthenticatedRequestAbstract
{
    const PATH = 'v2/applications/%s/deliveryinformation';

    /**
     * @var array
     */
    protected $data;

    public function __construct(\Zend\Http\ClientFactory $clientFactory, \ClassyLlama\Credova\Helper\Config $configHelper, \Psr\Log\LoggerInterface $logger, \ClassyLlama\Credova\Helper\Api $apiHelper, array $deliverInformation = [])
    {
        parent::__construct($clientFactory, $configHelper, $logger, $apiHelper);
        $this->data = $deliverInformation;
    }

    /**
     * Get request path
     *
     * @return string
     */
    protected function getPath(): string
    {
        return sprintf(self::PATH, $this->data['publicId']);
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
}
