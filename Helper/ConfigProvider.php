<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Asus\Product\Helper;

/**
 * Shopping cart helper
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;

class ConfigProvider extends \Magento\Framework\Url\Helper\Data
{

    /*
     * API auth user
     */
    const BR_API_BASE_URL = 'asus_related_product_settings/api/base_url';

    /*
     * API auth user
     */
    const BR_API_AUTH_USER = 'asus_related_product_settings/api/username';

    /*
     * API auth password
     */
    const BR_API_PASSWORD = 'asus_related_product_settings/api/password';

    /**
     * BR store pickup
     */
    const BR_StorePickup_enable = 'asus_related_product_settings/api/enable';


    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    protected $customer;

    protected $cache;

    /**
     * ConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\CacheInterface $cache,
        ScopeConfigInterface $scopeConfig,
        Session $customer
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->cache = $cache;
        $this->customer = $customer;
        parent::__construct($context);
    }

    public function getApiBaseUrl()
    {
        return $this->scopeConfig->getValue(self::BR_API_BASE_URL, ScopeInterface::SCOPE_STORE);
    }

    public function getApiUser()
    {
        return $this->scopeConfig->getValue(self::BR_API_AUTH_USER, ScopeInterface::SCOPE_STORE);
    }

    public function getApiPassword()
    {
        return $this->scopeConfig->getValue(self::BR_API_PASSWORD, ScopeInterface::SCOPE_STORE);
    }

    public function getRelatedProductEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(self::BR_StorePickup_enable, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * return user email
     * @return string
     */
    public function getUserEmails(){
        $email = $this->customer->getCustomer()->getEmail();
        return $email;
    }

    public function getCurrentUrl(){
        return $this->_urlBuilder->getCurrentUrl();
    }


    /**
     * Retrieve url for add product to cart
     *
     * @param $productId
     * @return string
     */
    public function getAddUrl($productId, $currentUrl)
    {

        $uenc = $this->urlEncoder->encode($currentUrl);
        $urlParamName = \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED;
        $routeParams = [
            $urlParamName => $uenc,
            'product' => $productId,
            '_secure' => $this->_getRequest()->isSecure()
        ];

        if ($this->_getRequest()->getRouteName() == 'checkout'
            && $this->_getRequest()->getControllerName() == 'cart'
        ) {
            $routeParams['in_cart'] = 1;
        }


        return $this->_getUrl('checkout/cart/add', $routeParams);
    }


    /**
     * Retrieve url for  notify me
     * @param $productId
     * @return string
     */
    public function getNotifyMeUrl($productId, $currentUrl)
    {
        $uenc = $this->urlEncoder->encode($currentUrl);
        $urlParamName = \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED;

        $routeParams = [
            $urlParamName => $uenc,
            'product_id' => $productId,
            '_secure' => $this->_getRequest()->isSecure()
        ];

        return $this->_getUrl('productalert/add/stock', $routeParams);
    }

    public function getUencValue($productId){
        $viewModel = $this->getViewModel();
        $postArray = $viewModel->getPostData(
            $this->escapeUrl($this->configProvider->getAddUrl($productId)),
            ['product' => $productId]
        );
        $value = $postArray['data'][ActionInterface::PARAM_NAME_URL_ENCODED];
        return $value;
    }






}