<?php

namespace Asus\Product\Controller\Product;

use Asus\Product\Model\Api\Adaptor\RelatedProductApi;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Json\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Asus\Product\Helper\ConfigProvider;

class FetchCartUrl extends Action
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
        RelatedProductApi $api,
        ConfigProvider $configProvider
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->connection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customer = $customer;
        $this->api = $api;
        $this->configProvider = $configProvider;
        return parent::__construct($context);
    }

    public function execute()
    {
        //get param
        $param = $this->getRequest()->getParams();
        $productId = $param['productId'];
        $currentUrl = $param['currentUrl'];
        $url = $this->configProvider->getAddUrl($productId, $currentUrl);
//        return $url;
        return $this->jsonResponse($url);
    }

    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }



}
