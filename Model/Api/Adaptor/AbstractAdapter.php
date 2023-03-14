<?php

namespace Asus55\Product\Model\Api\Adaptor;

use Asus55\Product\Model\Api\ConfigProvider;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\HTTP\Client\Curl;

abstract class AbstractAdapter
{
    const DEFAULT_HTTP_TIMEOUT = 10;

    /**
     * @var ConfigProvider $config
     */
    protected $config;

    /**
     * @var Json $converter
     */
    protected $converter;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var array $apiRespData
     */
    protected $apiRespData;

    protected $curl;

    protected $checkoutSession;

    /**
     * OpenPay constructor.
     *
     * @param ConfigProvider $configProvider
     * @param Json $converter
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConfigProvider $configProvider,
        Json $converter,
        LoggerInterface $logger,
        Curl $curl,
        CheckoutSession $checkoutSession
    ) {
        $this->config = $configProvider;
        $this->converter = $converter;
        $this->logger = $logger;
        $this->checkoutSession = $checkoutSession;
        $this->curl = $curl;
//        $this->initClient();
    }

    /**
     * @return mixed
     */
//    abstract protected function initClient();

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->config->getApiBaseUrl();
    }

    /**
     * @param string $path
     * @return string
     */
    protected function getEndpoint($path)
    {
        return rtrim($this->getBaseUrl(), '/') . '/' . trim($path, '/');
    }

    /**
     * @param string $body
     * @return array|bool|float|int|mixed|string|null
     */
    protected function parseBody(string $body)
    {
        try {
            return $this->converter->unserialize($body);
        } catch (\InvalidArgumentException $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($body);
        }
        return [];
    }




    public function __doRequest($url, $request) {
        $data = json_encode($request);

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST    => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Content-Length: " . strlen($data)
            ),
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);
        $this->logger->info("getRelatedProductAPI-url:".$url.";param:".json_encode($request));

        if($error) throw new \Exception('请求发生错误：' . $error);


        return $response;

    }




    /**
     * @param  $url
     * @param  $params
     * @param string $methods
     * @return array|bool|float|int|mixed|string|null
     */
    protected function getByHttpClient($url, $params)
    {
        $data = json_encode($params, JSON_UNESCAPED_SLASHES);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
        ));
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $response = curl_exec($curl);
        $errno = curl_errno($curl);
        if ($errno) {

            return false;
        }
        curl_close($curl);
        return $response;

//        curl_setopt_array($this->curl, array(
//            CURLOPT_URL => $url,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_CUSTOMREQUEST => $methods,
//            CURLOPT_POSTFIELDS => json_encode($params),
//            CURLOPT_RETURNTRANSFER => 1,
//            CURLOPT_HTTPHEADER => $this->headerInfo(true),
//        ));
//        $response = curl_exec($this->curl);
//        $err = curl_error($this->curl);
//
////        \HPOLS\Core\Helper\Data::writeLog('Response: ' . $response, 'kalunga_storepickup.log');
//
//        curl_close($this->curl);
//        if ($err) {
//            echo "cURL Error #:" . $err;
//            return json_decode($err, true);
//        } else {
//            return json_decode($response, true);
//        }
    }

    protected function logClientError($apiName, $requestUrl, $errorCode, $responseTxt)
    {
        $this->logger->error(
            var_export(compact("apiName", "requestUrl", "errorCode", "responseTxt"), true)
        );
    }

    /**
     * Generating header data.
     * @param bool $isRaw
     * @return array
     */
    protected function headerInfo($isRaw = false)
    {
        // "Content-type": "application/json; charset=utf-8"
        $headers["Content-Type"] = 'application/json;charset=utf-8;';
        if ($isRaw){
            return [
                "Content-Type: application/json;charset=utf-8;",
            ];
        }else{
            return $headers;
        }
    }

    private function getAuthorization()
    {
        $authorization = implode(
            ":",
            [
                $this->config->getApiUser(),
                $this->config->getApiPassword()
            ]
        );

        return base64_encode($authorization);
    }

}
