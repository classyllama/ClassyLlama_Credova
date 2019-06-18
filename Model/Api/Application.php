<?php

namespace ClassyLlama\Credova\Model\Api;

use ClassyLlama\Credova\Api\ApplicationInterface;
use ClassyLlama\Credova\Api\Data;
use ClassyLlama\Credova\Helper\Config;

class Application implements ApplicationInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \ClassyLlama\Credova\CredovaApi\Authenticated\ApplicationFactory
     */
    private $applicationRequestFactory;
    /**
     * @var Config
     */
    private $configHelper;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    public function __construct(
        \ClassyLlama\Credova\CredovaApi\Authenticated\ApplicationFactory $applicationRequestFactory,
        Config $configHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\UrlInterface $urlBuilder
    ) {
        $this->applicationRequestFactory = $applicationRequestFactory;
        $this->configHelper = $configHelper;
        $this->checkoutSession = $checkoutSession;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Creates an application in Credova and returns the public id
     *
     * @param Data\ApplicationInfoInterface $applicationInfo
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createApplication($applicationInfo)
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        $email = $applicationInfo->getEmail();

        if ($email === null) {
            $email = $this->checkoutSession->getQuote()->getCustomerEmail();
        }

        // TODO: Handle exception when number is malformed
        // Need to parse the phone number to ensure it's formatted properly for Credova's api
        $phoneNumber = $phoneUtil->parse($applicationInfo->getPhoneNumber(), "US");

        $data = [
            'storeCode' => $this->configHelper->getCredovaStoreCode(),
            'redirectUrl' => $this->urlBuilder->getRouteUrl('checkout/credovaCapture'),
            'firstName' => $applicationInfo->getFirstName(),
            'lastName' => $applicationInfo->getLastName(),
            'email' => $email,
            'mobilePhone' => $phoneNumber->getNationalNumber(),
            'products' => []
        ];

        // TODO: Handle when the shopping cart is empty
        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($this->checkoutSession->getQuote()->getItems() as $item) {
            $data['products'][] = [
                'id' => $item->getItemId(),
                'description' => $item->getName(),
                'quantity' => $item->getQty(),
                'value' => (float)$item->getBaseRowTotalInclTax() - (float)$item->getBaseDiscountAmount()
            ];
        }

        $shippingAddressInfo = $this->checkoutSession->getQuote()->getShippingAddress();
        $shippingAddressAmount = $shippingAddressInfo->getBaseShippingInclTax() - $shippingAddressInfo->getBaseShippingDiscountAmount();

        if ($shippingAddressAmount > 0) {
            $data['products'][] = [
                'id' => 'shipping',
                'description' => 'Shipping',
                'quantity' => 1,
                'value' => $shippingAddressAmount
            ];
        }

        if ($applicationInfo->getPublicId() !== null) {
            $data['publicId'] = $applicationInfo->getPublicId();
        }

        /** @var \ClassyLlama\Credova\CredovaApi\Authenticated\Application $request */
        $request = $this->applicationRequestFactory->create(['applicationInfo' => $data]);
        $response = $request->getResponseData();

        if (!array_key_exists('publicId', $response)) {
            // TODO: Properly handle API errors
            throw new \Exception($response);
        }

        return $response['publicId'];
    }
}
