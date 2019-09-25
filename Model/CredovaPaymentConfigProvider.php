<?php
/**
 * @category    ClassyLlama
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 * @author      sean.templeton
 */

namespace ClassyLlama\Credova\Model;

use ClassyLlama\Credova\Helper\Config;

class CredovaPaymentConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;
    /**
     * @var Config
     */
    private $credovaConfig;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param Config $credovaConfig
     */
    public function __construct(\Magento\Checkout\Model\Session $checkoutSession, \Magento\Quote\Api\CartRepositoryInterface $quoteRepository, Config $credovaConfig)
    {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->credovaConfig = $credovaConfig;
    }

    private function getQuoteData()
    {
        $quoteData = [];
        if ($this->checkoutSession->getQuote()->getId()) {
            $quote = $this->quoteRepository->get($this->checkoutSession->getQuote()->getId());
            $quoteData = $quote->toArray();
            $quoteData['is_virtual'] = $quote->getIsVirtual();

            if (!$quote->getCustomer()->getId()) {
                /** @var $quoteIdMask \Magento\Quote\Model\QuoteIdMask */
                $quoteIdMask = $this->quoteIdMaskFactory->create();
                $quoteData['entity_id'] = $quoteIdMask->load(
                    $this->checkoutSession->getQuote()->getId(),
                    'quote_id'
                )->getMaskedId();
            }
        }
        return $quoteData;
    }

    public function getApplicationId()
    {
        return [
            'publicId' => '<APPLICATION_PUBLIC_ID>',
            'storeCode' => $this->credovaConfig->getCredovaStoreCode(),
            'firstName' => "John",
            'middleInitial' => "S",
            'lastName' => "Hamilton",
            'dateOfBirth' => "20/05/1959",
            'mobilePhone' => "725-592-7418",
            'email' => "jhamilton@eml.com",
            'neededAmount' => 5000,
            'address' => [
                'street' => "100 Main Street",
                'suiteApartment' => "3A",
                'zipCode' => "15006",
                'city' => "California",
                'state' => "CA"
            ],
            'products' => [
                [
                    'id' => "123456",
                    'description' => "Lorem Ipsum",
                    'serialNumber' => "123456",
                    'quantity' => 1.00,
                    'value' => 1000,
                    'salesTax' => 10.00
                ]
            ],
            'redirectUrl' => "https://www.mystorewebsite.com/cart/15263321/checkpayment",
            'referenceNumber' => "1111122222333334444"
        ];
    }

    public function getConfig()
    {
        return [
            'credova' => [
                'store' => $this->credovaConfig->getCredovaStoreCode(),
                'environment' => $this->credovaConfig->getCredovaEnvironment()
            ]
        ];
    }
}
