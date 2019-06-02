<?php


namespace ClassyLlama\Credova\Plugin\Sales;


class ShipmentModel
{
    /**
     * @var \ClassyLlama\Credova\CredovaApi\Authenticated\DeliveryInformationFactory
     */
    private $deliveryInformationFactory;

    /**
     * @param \ClassyLlama\Credova\CredovaApi\Authenticated\DeliveryInformationFactory $deliveryInformationFactory
     */
    public function __construct(\ClassyLlama\Credova\CredovaApi\Authenticated\DeliveryInformationFactory $deliveryInformationFactory)
    {
        $this->deliveryInformationFactory = $deliveryInformationFactory;
    }

    function afterBeforeSave(\Magento\Sales\Model\Order\Shipment $subject, $result)
    {
        if ($subject->getOrder()->getPayment()->getMethod() !== 'credova') {
            return $result;
        }

        foreach ($subject->getOrder()->getInvoiceCollection()->getItems() as $invoice) {
            if(\count($subject->getTracks()) === 0) {
                throw new \Exception("Must specify a tracking number to ship a credova purchase");
            }

            $request = $this->deliveryInformationFactory->create(['deliverInformation' => [
                'publicId' => $invoice->getTransactionId(),
                // TODO: Get federal license number
                'federalLicenseNumber' => 'abc123',
                'method' => 'shipped',
                // TODO: Verify what to do with multiple tracking numbers
                'carrier' => $subject->getTracks()[0]->getCarrierCode(),
                'trackingNumber' => $subject->getTracks()[0]->getTrackNumber(),
                'address' => $subject->getShippingAddress()->getStreetLine(1),
                'city' => $subject->getShippingAddress()->getCity(),
                'state' => $subject->getShippingAddress()->getRegionCode(),
                'zip' => $subject->getShippingAddress()->getPostcode()
            ]]);

            $response = $request->getResponseData();

            if (!array_key_exists('publicId', $response)) {
                // TODO: Properly handle API errors
                throw new \Exception($response);
            }
        }

        return $result;
    }
}
