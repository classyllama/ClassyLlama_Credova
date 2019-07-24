<?php
/**
 * @category    ClassyLlama
 * @package
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 */

namespace ClassyLlama\Credova\Block;


use Magento\Framework\View\Asset\Repository as AssetRepository;

class PopulateFpx extends \Magento\Framework\View\Element\Template
{


    protected $assetRepository;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        AssetRepository $assetRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->assetRepository = $assetRepository;
        $this->request = $request;
    }




    public function getFpxConfig()
    {
        $output['fpxLogoImageUrl'] = $this->getViewFileUrl('ClassyLlama_Credova::images/fpx_logo.png');
        return $output;
    }

    public function getViewFileUrl($fileId, array $params = [])
    {
        $params = array_merge(['_secure' => $this->request->isSecure()], $params);
        return $this->assetRepository->getUrlWithParams($fileId, $params);
    }

}

?>