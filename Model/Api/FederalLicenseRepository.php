<?php

namespace ClassyLlama\Credova\Model\Api;

use ClassyLlama\Credova\Exception\CredovaApiException;
use Magento\Framework\Exception\NoSuchEntityException;

class FederalLicenseRepository implements \ClassyLlama\Credova\Api\FederalLicenseRepositoryInterface
{
    /**
     * @var \ClassyLlama\Credova\CredovaApi\Authenticated\CreateFederalLicenseFactory
     */
    protected $createLicenseRequestFactory;
    /**
     * @var \ClassyLlama\Credova\CredovaApi\Authenticated\GetFederalLicenseFactory
     */
    protected $getFederalLicenseRequestFactory;
    /**
     * @var \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory
     */
    protected $federalLicenseInterfaceFactory;

    /**
     * FederalLicenseRepository constructor.
     * @param \ClassyLlama\Credova\CredovaApi\Authenticated\CreateFederalLicenseFactory $createLicenseRequestFactory
     * @param \ClassyLlama\Credova\CredovaApi\Authenticated\GetFederalLicenseFactory $getFederalLicenseRequestFactory
     * @param \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory $federalLicenseInterfaceFactory
     */
    public function __construct(
        \ClassyLlama\Credova\CredovaApi\Authenticated\CreateFederalLicenseFactory $createLicenseRequestFactory,
        \ClassyLlama\Credova\CredovaApi\Authenticated\GetFederalLicenseFactory $getFederalLicenseRequestFactory,
        \ClassyLlama\Credova\Api\Data\FederalLicenseInterfaceFactory $federalLicenseInterfaceFactory
    ) {
        $this->createLicenseRequestFactory = $createLicenseRequestFactory;
        $this->getFederalLicenseRequestFactory = $getFederalLicenseRequestFactory;
        $this->federalLicenseInterfaceFactory = $federalLicenseInterfaceFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $federalLicenseNumber): \ClassyLlama\Credova\Api\Data\FederalLicenseInterface
    {
        /** @var \ClassyLlama\Credova\CredovaApi\Authenticated\GetFederalLicense $getFederalLicenseRequest */
        $getFederalLicenseRequest = $this->getFederalLicenseRequestFactory->create();

        $getFederalLicenseRequest->setLicenseNumber($federalLicenseNumber);

        try {
            $responseData = $getFederalLicenseRequest->getResponseData();
        } catch (CredovaApiException $e) {
            throw NoSuchEntityException::singleField('licenseNumber', $federalLicenseNumber);
        }

        /** @var \ClassyLlama\Credova\Api\Data\FederalLicenseInterface $federalLicense */
        $federalLicense = $this->federalLicenseInterfaceFactory->create();

        $federalLicense->setLicenseNumber($responseData['licenseNumber']);
        $federalLicense->setExpiration($responseData['expiration']);
        $federalLicense->setAddress($responseData['address']);
        $federalLicense->setAddress2($responseData['address2']);
        $federalLicense->setCity($responseData['city']);
        $federalLicense->setState($responseData['state']);
        $federalLicense->setZip($responseData['zip']);
        $federalLicense->setPublicId($responseData['public_id']);
        $federalLicense->setFilePublicId($responseData['filePublicId']);

        return $federalLicense;
    }

    /**
     * {@inheritDoc}
     * @throws CredovaApiException
     */
    public function create(\ClassyLlama\Credova\Api\Data\FederalLicenseInterface $federalLicense)
        : \ClassyLlama\Credova\Api\Data\FederalLicenseInterface
    {
        /** @var \ClassyLlama\Credova\CredovaApi\Authenticated\CreateFederalLicense $createLicenseRequest */
        $createLicenseRequest = $this->createLicenseRequestFactory->create();

        $createLicenseRequest->populateFromLicense($federalLicense);

        try {
            $responseData = $createLicenseRequest->getResponseData();

            $federalLicense->setPublicId($responseData['publicId']);
        } catch (CredovaApiException $e) {
            throw $e; // TODO: lame.
        }

        return $federalLicense;
    }
}
