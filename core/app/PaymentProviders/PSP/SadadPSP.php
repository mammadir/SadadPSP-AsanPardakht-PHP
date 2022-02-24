<?php

namespace App\PaymentProviders\PSP;

class SadadPSP
{
    protected $sadad_merchent;
    protected $sadad_terminal;
    protected $sadad_api_keys;
    public $paymentUrl;
    public $errorCode;
    public $errorMessage;

    /**
     * SadadPSP constructor.
     */
    public function __construct()
    {
        $this->sadad_merchent = site_config('sadad_merchent');
        $this->sadad_terminal = site_config('sadad_terminal');
        $this->sadad_api_keys = site_config('sadad_api_keys');
    }

    /**
     * @param $amount
     * @param null $mobile
     * @param null $factorNumber
     * @param null $description
     * @return mixed
     */
    public function send($amount, $factorNumber, $mobile = null, $description = null)
    {

        $result = curl_post('https://sadad.shaparak.ir/vpg/api/v0/Request/PaymentRequest', array(
            'TerminalId' => $this->sadad_terminal,
            'MerchantId' => $this->sadad_merchent,
            'Amount' => $amount,
            'SignData' => $this->encrypt_pkcs7("$this->sadad_terminal;$factorNumber;$amount", "$this->sadad_api_keys"),
            'ReturnUrl' => route('pg-callback-sadad', ['id' => $factorNumber]),
            'LocalDateTime' => date("m/d/Y g:i:s a"),
            'OrderId' => $factorNumber
        ));

        $result = json_decode($result, true);

        if (isset($result['ResCode']) && $result['ResCode'] == 0) {
            $Token = $result['Token'];
            $this->paymentUrl = "https://sadad.shaparak.ir/VPG/Purchase?Token=$Token";
        } else {
            var_dump($result['Description']);
        }
        if (isset($result['Description'])) {
            $this->errorMessage = $result['Description'];
        }

        return $result;
    }
    
    /**
     * @param $token
     * @return mixed
     */
    public function verify($token)
    {

        $result = curl_post('https://sadad.shaparak.ir/vpg/api/v0/Advice/Verify', array(
            'Token' => $token,
            'SignData' => $this->encrypt_pkcs7($token, $this->sadad_api_keys)
        ));

        $result = json_decode($result, true);
        
        return $result;
    }

    protected function encrypt_pkcs7($str, $key)
    {
        $key = base64_decode($key);
        $ciphertext = OpenSSL_encrypt($str, "DES-EDE3", $key, OPENSSL_RAW_DATA);
        return base64_encode($ciphertext);
    }

}
