<?php

namespace Asus55\Product\Model;

class Customcookie
{
    private $customCookieManager;

    private $customCookieMetadataFactory;

    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $customCookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $customCookieMetadataFactory)
    {
        $this->customCookieManager = $customCookieManager;
        $this->customCookieMetadataFactory = $customCookieMetadataFactory;
    }

    public function setCookie($cookieName, $cookieValue)
    {
        $customCookieMetadata = $this->customCookieMetadataFactory->createPublicCookieMetadata();
        $customCookieMetadata->setDurationOneYear();
        $customCookieMetadata->setPath('/');
        $customCookieMetadata->setHttpOnly(false);

        return $this->customCookieManager->setPublicCookie(
            $cookieName,
            $cookieValue,
            $customCookieMetadata
        );
    }

    public function getCookie($cookieName)
    {
        return $this->customCookieManager->getCookie(
            $cookieName
        );
    }
}