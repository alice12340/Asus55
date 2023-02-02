<?php

namespace Asus\Product\Controller\Product;

use Asus\Product\Block\Catalog\Product\View\RelatedProduct;
use Asus\Product\Model\Api\Adaptor\RelatedProductApi;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Json\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class SetProductId extends Action
{

    protected $jsonHelper;

    protected $connection;

    protected $scopeConfig;

    protected $storeManager;

    protected $relatedProductBlock;

    protected $customer;

    protected $api;

    protected $cache;


    public function __construct(
        Context $context,
        Data $jsonHelper,
        ResourceConnection $resourceConnection,
        ScopeConfigInterface $scopeConfig,
        Session $customer,
        StoreManagerInterface $storeManager,
        RelatedProductApi $api,
        RelatedProduct $relatedProductBlock,
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->connection = $resourceConnection;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->customer = $customer;
        $this->api = $api;
        $this->relatedProductBlock = $relatedProductBlock;
        $this->cache = $cache;
        return parent::__construct($context);
    }

    public function execute()
    {

        //get param
        $param = $this->getRequest()->getParams();
        $productId = $param['productId'];
        $this->cache->save($productId, 'relatedProductId');
        file_put_contents('ff.txt', print_r($this->cache->load('relatedProductId'), true));

    }


}
