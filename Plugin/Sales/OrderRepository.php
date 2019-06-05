<?php

namespace ClassyLlama\Credova\Plugin\Sales;

use Magento\Framework\Exception\LocalizedException;

class OrderRepository
{
    /**
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory
     */
    protected $orderExtensionInterfaceFactory;

    /**
     * OrderRepository constructor.
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionInterfaceFactory
     */
    public function __construct(
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionInterfaceFactory
    ) {
        $this->orderExtensionInterfaceFactory = $orderExtensionInterfaceFactory;
    }

    /**
     * Persist Credova extension attributes
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return array
     * @throws LocalizedException
     */
    public function beforeSave(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        if (!($order instanceof \Magento\Sales\Model\Order)) {
            throw new LocalizedException(
                __('Credova extension attribute persistence requires native order data model implementation.')
            );
        }

        $orderExtensionAttributes = $order->getExtensionAttributes();

        if ($orderExtensionAttributes !== null) {
            // Copy extension attribute data to column value
            $order->setCredovaApplicationId($orderExtensionAttributes->getCredovaApplicationId());
            $order->setCredovaFederalLicensePublicId($orderExtensionAttributes->getCredovaFederalLicensePublicId());
            $order->setCredovaFederalLicenseUploadStatus($orderExtensionAttributes->getCredovaFederalLicenseUploadStatus());
        }

        return [$order];
    }

    /**
     * @param \Magento\Sales\Model\Order[] $orders
     */
    private function attachCredovaExtensionAttributes(array $orders)
    {
        foreach ($orders as $order) {
            // Prepare extension attributes instance
            $orderExtensionAttributes = $order->getExtensionAttributes();

            if ($orderExtensionAttributes === null) {
                $orderExtensionAttributes = $this->orderExtensionInterfaceFactory->create();
            }

            // Copy column values to extension attributes
            $orderExtensionAttributes->setCredovaApplicationId($order->getCredovaApplicationId());
            $orderExtensionAttributes->setCredovaFederalLicensePublicId($order->getCredovaFederalLicensePublicId());
            $orderExtensionAttributes->setCredovaFederalLicenseUploadStatus($order->getCredovaFederalLicenseUploadStatus());

            // Done.
            $order->setExtensionAttributes($orderExtensionAttributes);
        }
    }

    /**
     * Retrieve Credova extension attributes
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws LocalizedException
     */
    public function afterGet(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        if (!($order instanceof \Magento\Sales\Model\Order)) {
            throw new LocalizedException(
                __('Credova extension attribute persistence requires native order data model implementation.')
            );
        }

        $this->attachCredovaExtensionAttributes([$order]);

        return $order;
    }

    /**
     * Retrieve Credova extension attributes
     *
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterface $orderSearchResult
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function afterGetList(
        \Magento\Sales\Api\OrderRepositoryInterface $subject,
        \Magento\Sales\Api\Data\OrderSearchResultInterface $orderSearchResult
    ) {
        $this->attachCredovaExtensionAttributes($orderSearchResult->getItems());

        return $orderSearchResult;
    }
}
