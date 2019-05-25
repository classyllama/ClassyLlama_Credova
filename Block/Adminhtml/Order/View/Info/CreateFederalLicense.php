<?php

namespace ClassyLlama\Credova\Block\Adminhtml\Order\View\Info;

class CreateFederalLicense extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        $order = $this->registry->registry('current_order');
        return $order;
    }

//    /**
//     * @return string
//     */
//    protected function _toHtml()
//    {
//        return ($this->getOrder()->getPayment()->getMethod() === Directpost::METHOD_CODE) ? parent::_toHtml() : '';
//    }
}
