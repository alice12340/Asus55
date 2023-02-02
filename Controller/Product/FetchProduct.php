<?php

namespace Asus\Product\Controller\Product;

use Asus\Product\Model\Api\Adaptor\RelatedProductApi;
use Asus\Product\Model\Api\ConfigProvider;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Json\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class FetchProduct extends Action
{

    protected $jsonHelper;

    protected $connection;

    protected $scopeConfig;

    protected $storeManager;

    protected $configProvider;

    protected $customer;

    protected $api;


    public function __construct(
        Context $context,
        Data $jsonHelper,
        ResourceConnection $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        Session $customer,
        StoreManagerInterface $storeManager,
        RelatedProductApi $api
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->connection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customer = $customer;
        $this->api = $api;
        return parent::__construct($context);
    }

    public function execute()
    {

        $customerEmail = $this->customer->getCustomer()->getEmail();
        //get param
        $param = $this->getRequest()->getParams();
//        $gaClientId = $param['ga_client_id'];
        $productId = $param['product_id'];
        $gaClientId = '4353453453.54354354353';
        $productId = "90YV0FA1-M0AA00";
//        echo base64_decode('aHR0cHM6Ly9zaG9wLmFzdXMuY29tL3VzL2FkZG9uL2NhcnQvYWRkL3VlbmMvYUhSMGNITTZMeTl6YUc5d0xtRnpkWE11WTI5dEwzVnpMMkZrWkc5dUwyeHBjM1JwYm1jdmFXNWtaWGd2YVdsa0x6STROVEkyTkRndmNXbGtMemczTVRjd09EVXYvcHJvZHVjdC8xNTMyNi8,');exit;

        $data = $this->api->getRelatedProducts($productId, $customerEmail, $gaClientId);



//        return $this->jsonResponse($data);
    }

    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = $this->_coreRegistry->registry('product');
        }
        return $this->_product;
    }
}
