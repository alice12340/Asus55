<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product description block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Asus\Product\Block\Catalog\Product\View;

use Asus\Product\Helper\ConfigProvider;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;


/**
 * Class RelatedProduct
 * @package Asus\Product\Block\Catelog\Product\View
 */
class RelatedProduct extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Product
     */
    protected $_product = null;

    protected $customer;

    protected $httpContext;

    protected $configProvider;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        Session $customer,
        \Magento\Framework\App\Http\Context $httpContext,
        ConfigProvider $configProvider,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->customer = $customer;
        $this->httpContext = $httpContext;
        $this->configProvider = $configProvider;
        parent::__construct($context, $data);
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

    /**
     * return product sku
     * @return string
     */
    public function getProductSku(){
        $product = $this->getProduct();
        $sku = $product->getSku();
        return $sku;
    }

    /**
     * return user email
     * @return string
     */
    public function getUserEmails(){
        $email = $this->customer->getCustomer()->getEmail();
        return $email;
    }

    /**
     * return customer email
     * @return mixed
     */
    public function getCustomerEmail(){
        return $this->httpContext->getValue('customer_email');
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


    public function getCartUrl($productId){
        return $this->configProvider->getAddUrl($productId);
    }


}
