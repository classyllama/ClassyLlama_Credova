<?php
/**
 * @category    ClassyLlama
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 * @author      sean.templeton
 */

namespace ClassyLlama\Credova\Block\Checkout;

use ClassyLlama\Credova\Helper\Config;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var Config
     */
    protected $credovaConfig;

    /**
     * @param \Magento\Checkout\Model\Session            $checkoutSession
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param Config                                     $credovaConfig
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        Config $credovaConfig
    )
    {
        $this->checkoutSession = $checkoutSession;
        $this->credovaConfig = $credovaConfig;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function process($jsLayout)
    {
        $minimumAmount = (float)$this->credovaConfig->getCredovaMinimumAmount();
        $grandTotal = (float)$this->checkoutSession->getQuote()->getBaseGrandTotal();

        if ($grandTotal < $minimumAmount) {
            unset($jsLayout["components"]["checkout"]["children"]["steps"]["children"]["billing-step"]["children"]["payment"]["children"]["renders"]["children"]["credova"]);
            return $jsLayout;
        }

        return $jsLayout;
    }
}
