<?php

namespace ClassyLlama\Credova\Model\Data;

use ClassyLlama\Credova\Api\Data\FederalLicenseInterface;

class FederalLicense extends \Magento\Framework\Api\AbstractExtensibleObject implements \ClassyLlama\Credova\Api\Data\FederalLicenseInterface
{
    /**
     * {@inheritDoc}
     */
    public function getLicenseNumber()
    {
        return $this->_get(self::LICENSE_NUMBER);
    }

    /**
     * {@inheritDoc}
     */
    public function setLicenseNumber($licenseNumber)
    {
        return $this->setData(self::LICENSE_NUMBER, $licenseNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function getLicenceName()
    {
        return $this->_get(self::LICENSE_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setLicenceName($licenceName)
    {
        return $this->setData(self::LICENSE_NAME, $licenceName);
    }

    /**
     * {@inheritDoc}
     */
    public function getExpiration()
    {
        return $this->_get(self::EXPIRATION);
    }

    /**
     * {@inheritDoc}
     */
    public function setExpiration($expiration)
    {
        return $this->setData(self::EXPIRATION, $expiration);
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress()
    {
        return $this->_get(self::ADDRESS);
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress($address)
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress2()
    {
        return $this->_get(self::ADDRESS_2);
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress2($address2)
    {
        return $this->setData(self::ADDRESS_2, $address2);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {
        return $this->_get(self::CITY);
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * {@inheritDoc}
     */
    public function getState()
    {
        return $this->_get(self::STATE);
    }

    /**
     * {@inheritDoc}
     */
    public function setState($state)
    {
        return $this->setData(self::STATE, $state);
    }

    /**
     * {@inheritDoc}
     */
    public function getZip()
    {
        return $this->_get(self::ZIP);
    }

    /**
     * {@inheritDoc}
     */
    public function setZip($zip)
    {
        return $this->setData(self::ZIP, $zip);
    }

    /**
     * {@inheritDoc}
     */
    public function getPublicId()
    {
        return $this->_get(self::PUBLIC_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setPublicId($publicId)
    {
        return $this->setData(self::PUBLIC_ID, $publicId);
    }

    /**
     * {@inheritDoc}
     */
    public function getFilePublicId()
    {
        return $this->_get(self::FILE_PUBLIC_ID);
    }

    /**
     * {@inheritDoc}
     */
    public function setFilePublicId($filePublicId)
    {
        return $this->setData(self::FILE_PUBLIC_ID, $filePublicId);
    }
}
