<?php

namespace ClassyLlama\Credova\Api\Data;

interface FederalLicenseInterface
{
    const LICENSE_NUMBER = 'license_number';
    const LICENSE_NAME = 'licence_name';
    const EXPIRATION = 'expiration';
    const ADDRESS = 'address';
    const ADDRESS_2 = 'address_2';
    const CITY = 'city';
    const STATE = 'state';
    const ZIP = 'zip';
    const PUBLIC_ID = 'public_id';
    const FILE_PUBLIC_ID = 'file_public_id';

    /**
     * Get license number
     *
     * @return string
     */
    public function getLicenseNumber();

    /**
     * Set license number
     *
     * @param string $licenseNumber
     * @return $this
     */
    public function setLicenseNumber($licenseNumber);

    /**
     * Get license name
     *
     * @return string
     */
    public function getLicenceName();

    /**
     * Set license name
     *
     * @param string $licenceName
     * @return $this
     */
    public function setLicenceName($licenceName);

    /**
     * Get expiration
     *
     * @return string
     */
    public function getExpiration();

    /**
     * Set expiration
     *
     * @param string $expiration
     * @return $this
     */
    public function setExpiration($expiration);

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress();

    /**
     * Set address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address);

    /**
     * Get address 2
     *
     * @return string
     */
    public function getAddress2();

    /**
     * Set address 2
     *
     * @param string $address2
     * @return $this
     */
    public function setAddress2($address2);

    /**
     * Get city
     *
     * @return string
     */
    public function getCity();

    /**
     * Set city
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * Get state
     *
     * @return string
     */
    public function getState();

    /**
     * Set state
     *
     * @param string $state
     * @return $this
     */
    public function setState($state);

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip();

    /**
     * Set zip
     *
     * @param string $zip
     * @return $this
     */
    public function setZip($zip);

    /**
     * Get license public ID
     *
     * @return string
     */
    public function getPublicId();

    /**
     * Set license public ID
     *
     * @param string $publicId
     * @return $this
     */
    public function setPublicId($publicId);

    /**
     * Get license file public ID
     *
     * @return string
     */
    public function getFilePublicId();

    /**
     * Set license file public ID
     *
     * @param string $filePublicId
     * @return $this
     */
    public function setFilePublicId($filePublicId);
}
