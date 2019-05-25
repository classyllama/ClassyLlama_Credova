<?php

namespace ClassyLlama\Credova\Plugin\Backend\Block\Widget\Button;

use Magento\Backend\Block\Widget\Button\Toolbar as OriginalToolbar;

class Toolbar
{
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
        if ($context instanceof \Magento\Sales\Block\Adminhtml\Order\View) {
            // Only take effect for order view block context

            $buttonList->add(
                'credova-create-federal-license',
                [
                    'label' => __('Create Federal License')
                ]
            );
        }

        return [$context, $buttonList];
    }
}
