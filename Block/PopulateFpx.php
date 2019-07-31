<?php
/**
 * @category    ClassyLlama
 * @package
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 */

namespace ClassyLlama\Credova\Block;


use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Tests\NamingConvention\true\string;

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

    /**
     * Set the photo URL path
     *
     * @return string
     */
    public function getFpxConfig()
    {
        $output['fpxLogoImageUrl'] = $this->getViewFileUrl('ClassyLlama_Credova::images/fpx_logo.png');
        return $output;
    }

    /**
     * A getter for static view file URL
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function getViewFileUrl($fileId, array $params = [])
    {
        $params = array_merge(['_secure' => $this->request->isSecure()], $params);
        return $this->assetRepository->getUrlWithParams($fileId, $params);
    }

}

?>