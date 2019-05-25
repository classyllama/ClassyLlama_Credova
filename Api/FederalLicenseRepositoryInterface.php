<?php

namespace ClassyLlama\Credova\Api;

interface FederalLicenseRepositoryInterface
{
    /**
     * Get Federal License by number
     *
     * @param string $federalLicenseNumber
     * @return \ClassyLlama\Credova\Api\Data\FederalLicenseInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(string $federalLicenseNumber) : \ClassyLlama\Credova\Api\Data\FederalLicenseInterface;

    /**
     * Create Federal License from provided data
     *
     * @param \ClassyLlama\Credova\Api\Data\FederalLicenseInterface $federalLicense
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \ClassyLlama\Credova\Api\Data\FederalLicenseInterface
     */
    public function create(\ClassyLlama\Credova\Api\Data\FederalLicenseInterface $federalLicense)
        : \ClassyLlama\Credova\Api\Data\FederalLicenseInterface;
}