<?php

namespace ClassyLlama\Credova\Controller\Adminhtml\Federallicense;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;

class Get extends \Magento\Backend\App\Action
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
     * Get constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface $federalLicenseRepository
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface $federalLicenseRepository
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->federalLicenseRepository = $federalLicenseRepository;
    }

    /**
     * Load license
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $licenseNumber = $this->getRequest()->getParam('license_number');

        $result = [];

        try {
            $license = $this->federalLicenseRepository->get($licenseNumber);

            $result['license_public_id'] = $license->getPublicId();
        } catch (NoSuchEntityException $e) {
            $result['message'] = __('No such license');
        }

        return $resultJson->setData($result);
    }
}
