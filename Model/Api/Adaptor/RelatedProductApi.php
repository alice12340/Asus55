<?php
/**
 * Created by PhpStorm.
 * User: leonzw
 * Date: 21-10-31
 */

namespace Asus55\Product\Model\Api\Adaptor;

class RelatedProductApi extends AbstractAdapter
{


    /** get related product from api
     * @param $productId
     * @param $customerEmail
     * @param $gaClientId
     * @param $currentUrl
     * @param $customerGroupId
     * @return array|bool|float|int|mixed|string|null
     */
    public function getRelatedProducts($productId, $customerEmail, $gaClientId, $currentUrl, $customerGroupId){
        try{
            $apiUrl = $this->config->getApiBaseUrl();
//            $enable = $this->config->getRelatedProductEnabled();
            $param = [
                'user'      => $gaClientId,
                'session'   => "detail-page-view",
                'product'   => [$productId],
                'useremail' => $customerEmail,
                'page_url'  => $currentUrl,
                'customer_group_id' => $customerGroupId,
            ];
//            $param = [
//                'user' => '4353453453.54354354353',
//                'session' => 'detail-page-view',
//                'product' => ['90DC0013-B40000'],
//                'usermail' => '',
//                'page_url'  => "https://shop.asus.com/us/rog/rog-phone-6.html",
//                'customer_group_id' => '',
//            ];

            $res = $this->__doRequest(
                $apiUrl,
                $param
            );
            $res = (array)json_decode($res);

            $experimentIds = $res['experimentIds'] ? $res['experimentIds'] : "";

            $skus = [];
            if (isset($res['results'])){
                foreach ($res['results'] as $v){
                    $v = (array)$v;
                    $skus[] = $v['id'];
                }
            }

            return array('skus' => $skus, 'experimentIds' => $experimentIds);

        }catch (\Exception $e){
            $this->logClientError(
                'getRelatedProduct',
                $apiUrl,
                "HTTP_ERROR_" . $e->getCode(),
                $e->getMessage()
            );
        }

    }



//    /**
//     * @return mixed
//     */
//    protected function initClient()
//    {
//        $this->curl = curl_init();
//    }
}
