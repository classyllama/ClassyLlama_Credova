<?php

namespace ClassyLlama\Credova\Plugin\Backend\Block\Widget\Button;

use Magento\Backend\Block\Widget\Button\Toolbar as OriginalToolbar;

class Toolbar
{
    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $authorization;

    /**
     * Toolbar constructor.
     * @param \Magento\Framework\AuthorizationInterface $authorization
     */
    public function __construct(
        \Magento\Framework\AuthorizationInterface $authorization
    ) {
        $this->authorization = $authorization;
    }

    /**
     * Add create federal license button
     *
     * @param OriginalToolbar $subject
     * @param \Magento\Framework\View\Element\AbstractBlock $context
     * @param \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
     * @return array
     */
    public function beforePushButtons(
        OriginalToolbar $subject,
        \Magento\Framework\View\Element\AbstractBlock $context,
        \Magento\Backend\Block\Widget\Button\ButtonList $buttonList
    ) {
        if (
            $context instanceof \Magento\Sales\Block\Adminhtml\Order\View
            && $this->authorization->isAllowed('ClassyLlama_Credova::credova_create_federal_license')
        ) {
            // Only take effect for order view block context

            if ($context->getOrder()->getPayment()->getMethod() == \ClassyLlama\Credova\Model\Method\Credova::CODE) {
                $buttonList->add(
                    'credova-create-federal-license',
                    [
                        'label' => __('Create Federal License')
                    ]
                );
            }
        }

        return [$context, $buttonList];
    }
}
