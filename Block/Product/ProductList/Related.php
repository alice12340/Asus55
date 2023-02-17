<?php

namespace Asus\Product\Block\Product\ProductList;

use Asus\Product\Model\Api\Adaptor\RelatedProductApi;
use Asus\Product\Model\Api\ConfigProvider;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;

class Related extends \Magento\Catalog\Block\Product\ProductList\Related
{

    /**
     * We can now set our limit here
     */
    const RELATED_LIMIT = 2;

    protected $productFactory;

    protected $api;

    protected $customer;

    protected $storeManager;

    protected $configProvider;

    protected $urlInterface;

    /**
     * Related constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Checkout\Model\ResourceModel\Cart $checkoutCart,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        RelatedProductApi $api,
        Session $cutomer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ConfigProvider $configProvider,
        \Magento\Framework\UrlInterface $urlInterface,
        array $data = []
    ) {
        $this->_checkoutCart = $checkoutCart;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_checkoutSession = $checkoutSession;
        $this->moduleManager = $moduleManager;
        $this->productFactory = $productFactory;
        $this->api = $api;
        $this->customer = $cutomer;
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
        $this->urlInterface = $urlInterface;

        parent::__construct(
            $context,
            $checkoutCart,
            $catalogProductVisibility,
            $checkoutSession,
            $moduleManager,
            $data
        );


    }

    /**
     * Prepare data
     * added limit for collection
     *
     * @return $this
     */
    protected function _prepareData()
    {
        $apiEnable = $this->configProvider->getRelatedProductEnabled($this->storeManager->getStore()->getStoreId());
        if ($apiEnable){
            $product = $this->getProduct();
            $productId = $product->getSku();
            $customerEmail = $this->customer->getCustomer()->getEmail();
            $customerGroupId = $this->customer->getCustomer()->getGroupId();
//            $_COOKIE['_ga'] = 'GA1.1.4353453453.54354354353';
            $gaClientId = preg_replace("/^.+\.(.+?\..+?)$/", "\\1", @$_COOKIE['_ga']);
            $currentUrl = $this->urlInterface->getCurrentUrl();
            $skus = $this->api->getRelatedProducts($productId, $customerEmail, $gaClientId, $currentUrl, $customerGroupId);
            $this->_itemCollection = $this->productFactory->create()
                ->addAttributeToSelect('required_options')
//                ->setPositionOrder()
                ->addStoreFilter()
                ->addAttributeToFilter('sku',array('in' => $skus));


            if ($this->moduleManager->isEnabled('Magento_Checkout')) {
                $this->_addProductAttributesAndPrices($this->_itemCollection);
            }
            $this->_itemCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

            $this->_itemCollection->load();

            foreach ($this->_itemCollection as $product) {
                $product->setDoNotUseCategoryId(true);
            }

            return $this;
        }
        else{
            parent::_prepareData();
        }
    }


}