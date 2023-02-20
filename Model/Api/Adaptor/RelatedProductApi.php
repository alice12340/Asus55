<?php
/**
 * Created by PhpStorm.
 * User: leonzw
 * Date: 21-10-31
 */

namespace Asus\Product\Model\Api\Adaptor;

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

            $res = $this->__doRequest(
                $apiUrl,
                $param
            );
            $res = (array)json_decode($res);

            $skus = [];
            foreach ($res['results'] as $v){
               $v = (array)$v;
                $skus[] = $v['id'];
            }

            return $skus;

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
