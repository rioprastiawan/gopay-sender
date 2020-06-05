<?php

/**
 * Gojek.php
 *
 * @category    Class
 * @package     Twlve\Gojek
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek;

use Twlve\Gojek\Http\Curl;
use Twlve\Gojek\Meta\Endpoint;
use Twlve\Gojek\Meta\Meta;

/**
 * Class Gojek
 *
 * @category    Class
 * @package     Twlve\Gojek
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class Gojek
{
    public static $appVersion;

    public static $location;

    public static $phoneModel;

    public static $deviceOs;

    public static $uniqueId;

    private static $accessToken;

    private static $headers = [];

    public function __construct($accessToken = null)
    {
        self::$headers['X-AppVersion']    = self::getAppVersion();
        self::$headers['X-Location']      = self::getLocation();
        self::$headers['X-PhoneModel']    = self::getPhoneModel();
        self::$headers['X-DeviceOS']      = self::getDeviceOs();
        self::$headers['X-Uniqueid']      = self::getUniqueId();

        if ($accessToken != null) {
            self::setAccessToken($accessToken);
        }
    }

    /**
     * Header request
     *
     * @return array
     */
    public static function _headers()
    {
        $headers = array();

        if (self::$headers !== null) {
            foreach (self::$headers as $key => $record) {
                $headers[$key] = $record;
            }
        }

        return $headers;
    }

    /**
     * Get app version
     *
     * @return mixed
     */
    public static function getAppVersion()
    {
        if (self::$appVersion === null) {
            self::$appVersion = Meta::APP_VERSION;
        }

        return self::$appVersion;
    }

    /**
     * Set app version
     *
     * @param  string $appVersion app version
     *
     * @return void
     */
    public static function setAppVersion($appVersion = null): void
    {
        self::$appVersion = $appVersion;
    }

    /**
     * Get location
     *
     * @return mixed
     */
    public static function getLocation()
    {
        if (self::$location === null) {
            self::$location = Meta::LOCATION;
        }

        return self::$location;
    }

    /**
     * Set location
     *
     * @param  string $location location
     *
     * @return void
     */
    public static function setLocation($location = null): void
    {
        self::$location = $location;
    }

    /**
     * Get phone model
     *
     * @return mixed
     */
    public static function getPhoneModel()
    {
        if (self::$phoneModel === null) {
            self::$phoneModel = Meta::PHONE_MODEL;
        }

        return self::$phoneModel;
    }

    /**
     * Set phone model
     *
     * @param  string $phoneModel phone model
     *
     * @return void
     */
    public static function setPhoneModel($phoneModel = null): void
    {
        self::$phoneModel = $phoneModel;
    }

    /**
     * Get device os
     *
     * @return mixed
     */
    public static function getDeviceOs()
    {
        if (self::$deviceOs === null) {
            self::$deviceOs = Meta::DEVICE_OS;
        }

        return self::$deviceOs;
    }

    /**
     * Set device os
     *
     * @param  string $deviceOs device os
     *
     * @return void
     */
    public static function setDeviceOs($deviceOs = null): void
    {
        self::$deviceOs = $deviceOs;
    }

    /**
     * Get unique id
     *
     * @return mixed
     */
    public static function getUniqueId()
    {
        if (self::$uniqueId === null) {
            self::$uniqueId = 'ac94e5d0e7f3f' . rand(111, 999);
        }

        return self::$uniqueId;
    }

    /**
     * Set unique id
     *
     * @param  string $uniqueId unique id
     *
     * @return void
     */
    public static function setUniqueId($uniqueId = null): void
    {
        self::$uniqueId = $uniqueId;
    }

    /**
     * Get access token
     *
     * @return string
     */
    public static function getAccessToken()
    {
        return self::$accessToken;
    }

    /**
     * Set access token
     *
     * @param  string $accessToken access token
     *
     * @return void
     */
    public static function setAccessToken(string $accessToken)
    {
        self::$accessToken = $accessToken;
    }

    /**
     * Authentication
     *
     * Login with Phone & Email
     * Auth OTP
     * Logout
     */

    /**
     * Login with phone
     *
     * @param  String                                   $phone
     *
     * @return \Twlve\Gojek\Response\LoginPhoneResponse
     */

    public static function loginPhone($phone)
    {
        $curl = new Curl();

        $data = [
            'phone' => $phone
        ];

        return $curl->post(Endpoint::BASE_ENDPOINT . Endpoint::LOGIN_PHONE, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Login with email
     *
     * @param  String                                   $email
     *
     * @return \Twlve\Gojek\Response\LoginEmailResponse
     */

    public static function loginEmail($email)
    {
        $curl = new Curl();

        $data = [
            'email' => $email
        ];

        return $curl->post(Endpoint::BASE_ENDPOINT . Endpoint::LOGIN_EMAIL, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Auth OTP
     *
     * @param  String                                  $loginToken
     * @param  String                                  $OTP
     *
     * @return \Twlve\Gojek\Response\LoginAuthResponse
     */

    public static function loginAuth($loginToken, $OTP)
    {
        $curl = new Curl();

        $data = [
            'scopes'        => 'gojek:customer:transaction gojek:customer:readonly',
            'grant_type'    => 'password',
            'login_token'   => $loginToken,
            'otp'           => $OTP,
            'client_id'     => 'gojek:cons:android',
            'client_secret' => '83415d06-ec4e-11e6-a41b-6c40088ab51e'
        ];

        return $curl->post(Endpoint::BASE_ENDPOINT . Endpoint::LOGIN_AUTH, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Logout GOJEK
     *
     * @return \Twlve\Gojek\Response\LogoutResponse
     */

    public function logout()
    {
        $curl = new Curl();

        self::$headers['Authorization'] = 'Bearer ' . self::getAccessToken();

        $data = [];

        return $curl->delete(Endpoint::BASE_ENDPOINT . Endpoint::LOGOUT, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Customer
     *
     * Data Customer
     * Update Customer
     */

    /**
     * Data Customer
     *
     * @return \Twlve\Gojek\Response\CustomerResponse
     */

    public function customer()
    {
        $curl = new Curl();

        self::$headers['Authorization'] = 'Bearer ' . self::getAccessToken();

        $data = [];

        return $curl->get(Endpoint::BASE_ENDPOINT . Endpoint::CUSTOMER, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Update Customer
     *
     * @param  String                                $name
     * @param  String                                $email
     * @param  String                                $phone
     *
     * @return \Twlve\Gojek\Response\CustomerUpdateResponse
     */

    public function updateCustomer($name, $email, $phone)
    {
        $curl = new Curl();

        self::$headers['Authorization'] = 'Bearer ' . self::getAccessToken();

        $data = [
            'name'  => $name,
            'email' => $email,
            'phone' => $phone
        ];

        return $curl->post(Endpoint::BASE_ENDPOINT . Endpoint::CUSTOMER_UPDATE, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Gopay
     *
     * Detail, History
     * Wallet Qr Code, Transfer
     */

    /**
     * Gopay Detail
     *
     * @return \Twlve\Gojek\Response\GopayDetailResponse
     */

    public function gopayDetail()
    {
        $curl = new Curl();

        self::$headers['Authorization'] = 'Bearer ' . self::getAccessToken();

        $data = [];

        return $curl->get(Endpoint::BASE_ENDPOINT . Endpoint::GOPAY_DETAIL, $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Get Wallet Code
     *
     * @param  String                                        $phoneTo
     *
     * @return \Twlve\Gojek\Response\CheckWalletCodeResponse
     */

    public function checkWalletCode($phoneTo)
    {
        $curl = new Curl();

        self::$headers['Authorization'] = 'Bearer ' . self::getAccessToken();

        $data = [];

        return $curl->get(Endpoint::BASE_ENDPOINT . Endpoint::GOPAY_WALLETCODE . '?phone_number=%2B62' . ltrim($phoneTo, '0'), $data, self::_headers())->getResponse()->getResult();
    }

    /**
     * Transfer GOPAY
     *
     * @param  String                                      $QRID
     * @param  String                                      $PIN
     * @param  Float                                       $amount
     * @param  String                                      $description
     *
     * @return \Twlve\Gojek\Response\GopayTransferResponse
     */

    public function gopayTransfer($QRID, $PIN, $amount, $description)
    {
        $curl = new Curl();

        self::$headers['Authorization'] = 'Bearer ' . self::getAccessToken();
        self::$headers['pin'] = $PIN;

        $data = [
            'qr_id'         => $QRID,
            'amount'        => $amount,
            'description'   => $description
        ];

        return $curl->post(Endpoint::BASE_ENDPOINT . Endpoint::GOPAY_TRANSFER, $data, self::_headers())->getResponse()->getResult();
    }
}
