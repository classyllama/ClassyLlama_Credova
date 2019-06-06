<?php

namespace ClassyLlama\Credova\Model\Api;

use ClassyLlama\Credova\CredovaApi\Authenticated\Application\UploadInvoiceRequestFactory;
use ClassyLlama\Credova\Exception\CouldNotUploadInvoiceException;
use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Model\Order\Pdf\Invoice;

class InvoiceManagement
{
    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var Invoice
     */
    private $invoicePdf;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var UploadInvoiceRequestFactory
     */
    private $uploadInvoiceRequestFactory;

    /**
     * @param AppState $appState
     * @param Invoice $invoicePdf
     * @param DateTime $dateTime
     * @param UploadInvoiceRequestFactory $uploadInvoiceRequestFactory
     */
    public function __construct(
        AppState $appState,
        Invoice $invoicePdf,
        DateTime $dateTime,
        UploadInvoiceRequestFactory $uploadInvoiceRequestFactory
    ) {
        $this->appState = $appState;
        $this->invoicePdf = $invoicePdf;
        $this->dateTime = $dateTime;
        $this->uploadInvoiceRequestFactory = $uploadInvoiceRequestFactory;
    }

    /**
     * Generate invoice PDF and upload it to Credova API
     *
     * @param string $publicId
     * @param InvoiceInterface $invoice
     * @param null|string $filename
     * @return void
     * @throws CouldNotUploadInvoiceException
     */
    public function uploadInvoice($publicId, $invoice, $filename = null)
    {
        try {
            if (!$filename) {
                $filename = 'invoice' . $this->dateTime->date('Y-m-d_H-i-s') . '.pdf';
            }

            /** @var \ClassyLlama\Credova\CredovaApi\Authenticated\Application\UploadInvoiceRequest $request */
            $request = $this->uploadInvoiceRequestFactory->create([
                'publicId' => $publicId,
                'fileName' => $filename,
                'fileContent' => $this->renderPdf($invoice),
            ]);

            $request->getResponse();
        } catch (\Exception $e) {
            throw new CouldNotUploadInvoiceException(
                __('Unable to upload invoice to Credova API'),
                $e
            );
        }
    }

    /**
     * Render invoice pdf file content
     *
     * @param InvoiceInterface $invoice
     * @return string
     */
    private function renderPdf($invoice)
    {
        $getPdf = function () use ($invoice) {
            return $this->invoicePdf->getPdf([$invoice]);
        };

        $areaCode = $this->appState->getAreaCode();
        if ($areaCode === Area::AREA_FRONTEND) {
            $pdf = $getPdf();
        } else {
            /**
             * For other areas (e.g. webapi_rest) we must emulate frontend area
             * because webapi areas do not have appropriate template
             * @see \Magento\Payment\Block\Info::toPdf
             * Otherwise PDF rendering is failing
             */
            $pdf = $this->appState->emulateAreaCode(Area::AREA_FRONTEND, $getPdf);
        }

        return $pdf->render();
    }
}
