<?php

namespace ClassyLlama\Credova\Controller\Adminhtml\Federallicense;

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
    const ADMIN_RESOURCE = 'ClassyLlama_Credova::credova';

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
     * Api constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface $federalLicenseRepository
     * @param \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory $federalLicenseFactory
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionInterfaceFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface $federalLicenseRepository,
        \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory $federalLicenseFactory,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionInterfaceFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->federalLicenseRepository = $federalLicenseRepository;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->federalLicenseFactory = $federalLicenseFactory;
        $this->orderExtensionInterfaceFactory = $orderExtensionInterfaceFactory;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Set license number on order
     *
     * @param int $orderId
     * @param string $licenseNumber
     */
    private function setLicenseNumberOnOrder(int $orderId, string $licenseNumber)
    {
        $order = $this->orderRepository->get($orderId);

        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionInterfaceFactory->create();
        }

        $extensionAttributes->setCredovaFederalLicenseNumber($licenseNumber);

        $order->setExtensionAttributes($extensionAttributes);

        $this->orderRepository->save($order);
    }

    /**
     * Get and associate federal license
     *
     * @return array
     */
    private function getLicense() : array
    {
        $licenseNumber = $this->getRequest()->getParam('license_number');

        try {
            $license = $this->federalLicenseRepository->get($licenseNumber);

            // License successfully found. Go ahead and set number on order.
            $this->setLicenseNumberOnOrder($this->getRequest()->getParam('order_id'), $license->getLicenseNumber());

            return [
                'status' => 'success',
                'license_public_id' => $license->getPublicId()
            ];
        } catch (NoSuchEntityException $e) {
            return ['status' => __('error')];
        }
    }

    /**
     * Create and associate federal license
     *
     * @return array
     */
    private function createLicense() : array
    {
        /** @var \ClassyLlama\Credova\Api\Data\FederalLicenseInterface $license */
        $license = $this->federalLicenseFactory->create();

        $licenseData = $this->getRequest()->getParams();

        $this->dataObjectHelper->populateWithArray(
            $license,
            $licenseData,
            'ClassyLlama\Credova\Api\Data\FederalLicenseInterface'
        );

        try {
            $this->federalLicenseRepository->create($license);

            // License successfully created. Go ahead and set number on order.
            $this->setLicenseNumberOnOrder($this->getRequest()->getParam('order_id'), $license->getLicenseNumber());

            return ['status' => __('success')];
        } catch (CouldNotSaveException $e) {
            return [
                'status' => __('error'),
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Perform federal license actions
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        // NB: the request interface does not supply sufficient information,
        // so using implementation directly.
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();

        $resultData = [];

        switch ($request->getParam('action')) {
            case 'get':
                $resultData = $this->getLicense();
                break;
            case 'create':
                $resultData = $this->createLicense();
                break;
            default:
                throw new LocalizedException(__('Invalid license API action.'));
        }

        return $resultJson->setData($resultData);
    }
}
