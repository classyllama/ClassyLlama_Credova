<?php

namespace ClassyLlama\Credova\Controller\Adminhtml\UploadFederalLicense;

use ClassyLlama\Credova\CredovaApi\Authenticated\UploadFederalLicense;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * This controller is a thin wrapper around the Federal License Repository.
 *
 * Requests to this controller should really use the repository's webAPI directly -- however,
 * due to issue #14297, admin session authentication is currently broken in the core,
 * making unreasonable to make webAPI requests from JS in the admin context.
 *
 * Since this controller already exists, it's also being used to set license numbers on orders,
 * to avoid an extra request when all the context is already known.
 */
class Api extends \Magento\Backend\App\Action
{
    /**
     * ACL resource ID
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'ClassyLlama_Credova::credova_create_federal_license';

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface
     */
    protected $federalLicenseRepository;
    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;
    /**
     * @var \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory
     */
    protected $federalLicenseFactory;
    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    protected $orderExtensionInterfaceFactory;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var UploadFederalLicense
     */
    private $uploadFederalLicense;

    /**
     * Api constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface $federalLicenseRepository
     * @param \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory $federalLicenseFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionInterfaceFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param UploadFederalLicense $uploadFederalLicense
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface $federalLicenseRepository,
        \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory $federalLicenseFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionInterfaceFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        UploadFederalLicense $uploadFederalLicense
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->federalLicenseRepository = $federalLicenseRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->federalLicenseFactory = $federalLicenseFactory;
        $this->orderExtensionInterfaceFactory = $orderExtensionInterfaceFactory;
        $this->orderRepository = $orderRepository;
        $this->uploadFederalLicense = $uploadFederalLicense;
    }

    /**
     * Set license number on order
     *
     * @param int $orderId
     * @param string $licensePublicId
     */
    private function setPublicIdOnOrder(int $orderId, string $licensePublicId)
    {
        $order = $this->orderRepository->get($orderId);

        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionInterfaceFactory->create();
        }

        $extensionAttributes->setCredovaFederalLicensePublicId($licensePublicId);

        $order->setExtensionAttributes($extensionAttributes);

        $this->orderRepository->save($order);
    }







    public function uploadNewLicence(){

    }


    /**
     * Perform federal license actions
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws LocalizedException
     */
    public function execute()
    {
        $request = $this->getRequest();

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        /** @var \Magento\Framework\App\Request\Http $request */
        $licence = $request->getParam('public_license');
        $file = $request->getParam('file');
        $this->uploadFederalLicense->setData(
            ["file" => $file]
        );

        $request = $this->uploadFederalLicense->getResponse();
//



        return $resultJson->setData(["wow so cool"]);
    }
}
