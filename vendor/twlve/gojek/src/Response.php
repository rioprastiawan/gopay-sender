<?php

/**
 * Response.php
 *
 * @category    Class
 * @package     Twlve\Gojek
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek;

use stdClass;
use Twlve\Gojek\Meta\Endpoint;
use Twlve\Gojek\Response\DefaultResponse;
use Twlve\Gojek\Response\NoConnectionResponse;

/**
 * Class Response
 *
 * @category    Class
 * @package     Twlve\Gojek
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class Response
{
    /**
     * store Class
     *
     * @var array
     */
    public $storeClass = [
        // Authentication
        Endpoint::BASE_ENDPOINT . Endpoint::LOGIN_EMAIL         => 'Twlve\Gojek\Response\LoginEmailResponse',
        Endpoint::BASE_ENDPOINT . Endpoint::LOGIN_PHONE         => 'Twlve\Gojek\Response\LoginPhoneResponse',
        Endpoint::BASE_ENDPOINT . Endpoint::LOGIN_AUTH          => 'Twlve\Gojek\Response\LoginAuthResponse',
        Endpoint::BASE_ENDPOINT . Endpoint::LOGOUT              => 'Twlve\Gojek\Response\LogoutResponse',

        // Customer
        Endpoint::BASE_ENDPOINT . Endpoint::CUSTOMER            => 'Twlve\Gojek\Response\CustomerResponse',
        Endpoint::BASE_ENDPOINT . Endpoint::CUSTOMER_UPDATE     => 'Twlve\Gojek\Response\CustomerUpdateResponse',

        // Gopay
        Endpoint::BASE_ENDPOINT . Endpoint::GOPAY_DETAIL        => 'Twlve\Gojek\Response\GopayDetailResponse',
        Endpoint::BASE_ENDPOINT . Endpoint::GOPAY_TRANSFER      => 'Twlve\Gojek\Response\GopayTransferResponse',
    ];

    private $response;

    /**
     * Parse response init
     *
     * @param mixed  $result
     * @param string $url
     */
    public function __construct($result, $httpcode, $url)
    {
        $response = json_decode($result);

        $parts = parse_url($url);

        if($httpcode == 0) {
            $this->response = new NoConnectionResponse();
        } else {
            if ($parts['path'] == '/wallet/qr-code') {
                $this->response = new \Twlve\Gojek\Response\CheckWalletCodeResponse($response);
            } else {
                $this->response = (!empty($this->storeClass[$url])) ? new $this->storeClass[$url]($response) : new DefaultResponse($response);
            }
        }
    }

    /**
     * Get response following by class
     *
     * @return void
     */
    public function getResponse()
    {
        return $this->response;
    }
}
