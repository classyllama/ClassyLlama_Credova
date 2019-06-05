<?php

namespace ClassyLlama\Credova\CredovaApi\Authenticated;

use ClassyLlama\Credova\Exception\CredovaApiException;

class CreateApplicationRequest extends \ClassyLlama\Credova\CredovaApi\Authenticated\AuthenticatedRequestAbstract
{
    const PATH = 'v2/applications';

    protected $requestData = [];

    /**
     * {@inheritdoc}
     */
    protected function getPath(): string
    {
        return static::PATH;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMethod(): string
    {
        return \Zend\Http\Request::METHOD_POST;
    }

    /**
     * Extract application object into array
     *
     * @param \ClassyLlama\Credova\Api\Data\ApplicationInterface $application
     */
    protected function prepareRequestData(\ClassyLlama\Credova\Api\Data\ApplicationInterface $application)
    {
        $this->requestData = [
            'publicId' => $application->getPublicId(),
            'storeCode' => $application->getStoreCode(),
            'firstName' => $application->getFirstName(),
            'middleInitial' => $application->getMiddleInitial(),
            'lastName' => $application->getLastName(),
            'dateOfBirth' => $application->getDateOfBirth(),
            'mobilePhone' => $application->getMobilePHone(),
            'email' => $application->getEmail(),
            'neededAmount' => $application->getNeededAmount(),
            'redirectUrl' => $application->getRedirectUrl(),
            'referenceNumber' => $application->getReferenceNumber(),
            'address' => [
                'street' => $application->getAddress()->getStreet(),
                'suiteApartment' => $application->getAddress()->getSuiteApartment(),
                'zipCode' => $application->getAddress()->getZipCode(),
                'city' => $application->getAddress()->getCity(),
                'state' => $application->getAddress()->getState(),
            ]
        ];

        $products = [];
        foreach ($application->getProducts() as $product) {
            $products[] = [
                'id' => $product->getId(),
                'description' => $product->getDescription(),
                'serialNumber' => $product->getSerialNumber(),
                'quantity' => $product->getQuantity(),
                'value' => $product->getValue(),
                'salesTax' => $product->getSalesTax(),
            ];
        }

        $this->requestData['products'] = $products;
    }

    /**
     * Create Credova application and return profile ID and redirect URL
     *
     * @param \ClassyLlama\Credova\Api\Data\ApplicationInterface $application
     * @return array
     * @throws \ClassyLlama\Credova\Exception\CredovaApiException
     */
    public function createApplication(\ClassyLlama\Credova\Api\Data\ApplicationInterface $application) : array
    {
        $this->prepareRequestData($application);

        return $this->getResponseData();
    }

    /**
     * {@inheritdoc}
     * @throws CredovaApiException
     */
    protected function getData() : array
    {
        if (empty($this->requestData)) {
            throw new CredovaApiException(__('Create application request without application data.'));
        }

        return $this->requestData;
    }


}