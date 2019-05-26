<?php


namespace ClassyLlama\Credova\CredovaApi\Authenticated;

class CreateFederalLicense extends AuthenticatedRequestAbstract
{
    const PATH = 'v2/federallicense/';

    /**
     * {@inheritDoc}
     */
    protected function getPath(): string
    {
        return static::PATH;
    }

    /**
     * {@inheritDoc}
     */
    protected function getMethod(): string
    {
        return \Zend\Http\Request::METHOD_POST;
    }

    /**
     * Populate data from license instance
     *
     * @param \ClassyLlama\Credova\Api\Data\FederalLicenseInterface $federalLicense
     */
    public function populateFromLicense(\ClassyLlama\Credova\Api\Data\FederalLicenseInterface $federalLicense)
    {
        $this->setData([
            'licenseNumber' => $federalLicense->getLicenseNumber(),
            'licenceName' => $federalLicense->getLicenceName(),
            'expiration' => $federalLicense->getExpiration(),
            'address' => $federalLicense->getAddress(),
            'address2' => $federalLicense->getAddress2(),
            'city' => $federalLicense->getCity(),
            'state' => $federalLicense->getState(),
            'zip' => $federalLicense->getZip(),
        ]);
    }
}
