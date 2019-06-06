<?php

namespace ClassyLlama\Credova\Observer\Sales\Order;

use ClassyLlama\Credova\Exception\CouldNotUploadInvoiceException;
use ClassyLlama\Credova\Model\Api\InvoiceManagement;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class PaymentPlaceEnd implements ObserverInterface
{
    /**
     * @var InvoiceManagement
     */
    private $invoiceManagement;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param InvoiceManagement $invoiceManagement
     * @param LoggerInterface $logger
     */
    public function __construct(
        InvoiceManagement $invoiceManagement,
        LoggerInterface $logger
    )
    {
        $this->invoiceManagement = $invoiceManagement;
        $this->logger = $logger;
    }

    /**
     * Upload created invoice to Credova API once payment is successfully done.
     *
     * Credova application public ID gets set to order instance during capture process
     * @param Observer $observer
     * @return void
     * @see \ClassyLlama\Credova\Model\Method\Credova::capture
     *
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $observer->getEvent()->getPayment();
        /** @var \Magento\Sales\Model\Order\Invoice $invoice */
        $invoice = $payment->getCreatedInvoice();

        if (!$invoice || $payment->getMethod() !== 'credova') {
            return;
        }

        try {
            $this->invoiceManagement->uploadInvoice($invoice->getTransactionId(), $invoice);
        } catch (CouldNotUploadInvoiceException $e) {
            // do not break order placement flow if invoice upload has failed
            $this->logger->debug($e->getMessage(), [
                'trace' => $e->getTrace()
            ]);
        }
    }
}
