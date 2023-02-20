<?php
/**
 * Created by PhpStorm.
 * User: leonzw
 * Date: 21-10-31
 * Time: 下午5:58
 */

namespace Asus\Product\Model\Api;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigProvider
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
    private $scopeConfig;

    /**
     * ConfigProvider constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getApiBaseUrl()
    {
        return $this->scopeConfig->getValue(self::BR_API_BASE_URL, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getApiUser()
    {
        return $this->scopeConfig->getValue(self::BR_API_AUTH_USER, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getApiPassword()
    {
        return $this->scopeConfig->getValue(self::BR_API_PASSWORD, ScopeInterface::SCOPE_WEBSITE);
    }

    public function getRelatedProductEnabled()
    {
        return $this->scopeConfig->getValue(self::BR_StorePickup_enable,ScopeInterface::SCOPE_WEBSITE);
    }


    

}