<?php

namespace ClassyLlama\Credova\CredovaApi\Authenticated;

use ClassyLlama\Credova\Exception\CredovaApiException;

class GetFederalLicense extends AuthenticatedRequestAbstract
{
    const PATH = 'v2/federallicense/licensenumber/%s';

    /**
     * {@inheritDoc}
     * @throws CredovaApiException
     */
    protected function getPath(): string
    {
        if (!isset($this->getData()['license_number'])) {
            throw new CredovaApiException(__('License number not set before license retrieval.'));
        }

        return sprintf(static::PATH, $this->getData()['license_number']);
    }

    /**
     * {@inheritDoc}
     */
    protected function getMethod(): string
    {
        return \Zend\Http\Request::METHOD_GET;
    }

    /**
     * Set license number to be retrieved
     *
     * @param string $licenseNumber
     */
    public function setLicenseNumber(string $licenseNumber)
    {
        $this->setData(['license_number' => $licenseNumber]);
    }
}
