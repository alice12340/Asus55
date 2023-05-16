<?php

namespace Asus55\Product\Block\Product\ProductList;

use Asus55\Product\Model\Api\Adaptor\RelatedProductApi;
use Asus55\Product\Model\Api\ConfigProvider;
use Asus55\Product\Model\Customcookie;
use Magento\Customer\Model\Session;

class Related extends \Magento\Catalog\Block\Product\ProductList\Related
{

    protected $productFactory;

    protected $api;

    protected $customer;

    protected $storeManager;

    protected $configProvider;

    protected $urlInterface;

    protected $collection;

    protected $customCookie;

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
        \Magento\Framework\Data\Collection $collection,
        Customcookie $customCookie,
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
        $this->collection = $collection;
        $this->customCookie = $customCookie;

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
        $apiEnable = $this->configProvider->getRelatedProductEnabled($this->storeManager->getStore()->getWebsiteId());
        if ($apiEnable){
            $product = $this->getProduct();
            $productId = $product->getSku();
            $customerEmail = $this->getUserId();
            $customerGroupId = $this->customer->getCustomer()->getGroupId();
//            $_COOKIE['_ga'] = 'GA1.1.4353453453.54354354353';
            $gaClientId = isset($_COOKIE['_ga']) ? preg_replace("/^.+\.(.+?\..+?)$/", "\\1", @$_COOKIE['_ga']) : '';
            $currentUrl = $this->urlInterface->getCurrentUrl();
            $apiRes = $this->api->getRelatedProducts($productId, $customerEmail, $gaClientId, $currentUrl, $customerGroupId);
            $skus = $apiRes['skus'];
            $experimentIds = $apiRes['experimentIds'];
            $this->customCookie->setCookie('experimentIds', $experimentIds);
            $itemCollectionNoOrder = $this->productFactory->create()
                ->addAttributeToSelect('required_options')
//                ->setPositionOrder()
                ->addStoreFilter()
                ->addAttributeToFilter('sku',array('in' => $skus));


            if ($this->moduleManager->isEnabled('Magento_Checkout')) {
                $this->_addProductAttributesAndPrices($itemCollectionNoOrder);
            }
            $itemCollectionNoOrder->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

            $itemCollectionNoOrder->load();

            foreach ($itemCollectionNoOrder as $product) {
                $product->setDoNotUseCategoryId(true);
            }


            // sort product by sku which api returned
            foreach ($skus as $sku){
                foreach ($itemCollectionNoOrder->getItems() as $product){
                    if ($sku === $product->getSku()){
                        $this->collection->addItem($product);
                    }
                }
            }
            $this->_itemCollection = $this->collection;

            return $this;
        }
        else{
            parent::_prepareData();
        }
    }

    /**
     * @desc get User Id
     * @return bool|string|string[]|null
     */
    function getUserId(){
//        $_COOKIE['APREFORZ'] = '5544BEC0-CC3A-4655-B728-C8235B2E33E7TJQE';
        if (!isset($_COOKIE['APREFORZ'])) {
            return '';
        }

        $cd = preg_replace('/^\s+/', '', $_COOKIE['APREFORZ']);
        $cd = $this->unescape($cd);

        if ($cd && strlen($cd) > 4){
            return substr($cd, 0, strlen($cd) - 4);
        }else{
            return $cd;
        }

    }


    function unescape($str)
    {
        $str = rawurldecode($str);

        preg_match_all("/%u.{4}|.{4};|d+;|.+/U",$str,$r);

        $ar = $r[0];

        foreach($ar as $k=>$v)

        {
            if(substr($v,0,2) == "%u")

            {
                //$ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,-4)));

                $ar[$k] = mb_convert_encoding(pack("H4",substr($v,-4)),"UTF-8","UCS-2");

            }

            elseif(substr($v,0,3) == "")

            {
                //$ar[$k] = iconv("UCS-2","UTF-8",pack("H4",substr($v,3,-1)));

                $ar[$k] = mb_convert_encoding(pack("H4",substr($v,3,-1)),"UTF-8","UCS-2");

            }

            elseif(substr($v,0,2) == "")

            {
                //$ar[$k] = iconv("UCS-2","UTF-8",pack("n",substr($v,2,-1)));

                $ar[$k] = mb_convert_encoding(pack("n",substr($v,2,-1)),"UTF-8","UCS-2");

            }

        }

        return join("",$ar);

    }


}