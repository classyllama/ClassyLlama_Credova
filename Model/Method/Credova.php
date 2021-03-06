<?php
/**
 * @category    ClassyLlama
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 * @author      sean.templeton
 */

namespace ClassyLlama\Credova\Model\Method;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\DataObject;
use Magento\Payment\Model\InfoInterface;

class Credova extends \Magento\Payment\Model\Method\AbstractMethod
{
    const ADDITIONAL_INFO_APPLICATION_ID_KEY = 'credova_application_id';
    const CODE = 'credova';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::CODE;

    protected $applicationId = null;

    /**
     * @var \ClassyLlama\Credova\CredovaApi\Authenticated\RequestReturnFactory
     */
    private $requestReturnFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \ClassyLlama\Credova\CredovaApi\Authenticated\RequestReturnFactory $requestReturnFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        DirectoryHelper $directory = null
    )
    {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $resource, $resourceCollection, $data, $directory);
        $this->requestReturnFactory = $requestReturnFactory;
    }

    /**
     * Capture payment abstract method
     *
     * @param \Magento\Framework\DataObject|InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function capture(InfoInterface $payment, $amount)
    {
        $applicationId =
            $this->getInfoInstance()->getAdditionalInformation(self::ADDITIONAL_INFO_APPLICATION_ID_KEY);

        if ($applicationId !== null) {
            $payment->setLastTransId($applicationId);
        }

        parent::capture($payment, $amount);
    }

    public function assignData(DataObject $data)
    {
        parent::assignData($data);

        // TODO: look before you leap
        $applicationId = $data->getData('additional_data')['application_id'];

        // Set application ID on info instance to persist for later use
        $this->getInfoInstance()->setAdditionalInformation(self::ADDITIONAL_INFO_APPLICATION_ID_KEY, $applicationId);

        return $this;
    }

    public function refund(InfoInterface $payment, $amount)
    {
        $request = $this->requestReturnFactory->create(['applicationId' => $payment->getLastTransId()]);
        $response = $request->getResponseData();

        if (array_key_exists('errors', $response)) {
            throw new \Exception($response['errors']);
        }

        return parent::refund($payment, $amount); // TODO: Change the autogenerated stub
    }

    public function canRefund()
    {
        return true;
    }

    public function canCapture()
    {
        return true;
    }
}
