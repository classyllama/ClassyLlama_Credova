<?php

namespace ClassyLlama\Credova\Controller\Adminhtml\UploadFederalLicense;

use ClassyLlama\Credova\CredovaApi\Authenticated\UploadFederalLicense;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use function PHPSTORM_META\type;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
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
class Api extends \Magento\Backend\App\Action implements CsrfAwareActionInterface
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
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var Action\Context
     */
    private $context;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $fileUploaderFactory;

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
        UploadFederalLicense $uploadFederalLicense
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->uploadFederalLicense = $uploadFederalLicense;
        $this->context = $context;

    }


    /**
     * Perform federal license actions
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws LocalizedException
     */
    public function execute()
    {
        try {
            $request = $this->getRequest();

            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            /** @var \Magento\Framework\App\Request\Http $request */
            $licence = $request->getParam('public_license');
            $files = $request->getFiles();
            $this->uploadFederalLicense->setData(['file'=>$files['file_upload']['tmp_name']]);
            $response = $this->uploadFederalLicense->getResponse();

            return $resultJson->setData(['status' => __('success')]);
        } catch (CouldNotSaveException $e) {
            return $resultJson->setData([
                'status' => __('error'),
                'message' => $e->getMessage()
            ]);
        }



    }
}
