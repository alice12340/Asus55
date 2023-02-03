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
     * @return array|bool|float|int|mixed|string|null
     */
    public function getRelatedProducts($productId, $customerEmail, $gaClientId){
        try{
            $apiUrl = $this->config->getApiBaseUrl();
            $enable = $this->config->getRelatedProductEnabled();

            $param = [
                'user'      => $gaClientId,
                'session'   => "detail-page-view",
                'product'   => [$productId],
                'useremail' => $customerEmail,
            ];

//            $param = array(
//                'user' => '4353453453.54354354353',
//                'session' => 'detail-page-view',
//                'product' => ['90YV0FA1-M0AA00'],
//                'usermail' => '',
//            );
//


            if ($enable){

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
            }else{
                return false;
            }
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
