<?php

/**
 * Endpoint.php
 *
 * @category    Class
 * @package     Twlve\Gojek\Meta
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek\Meta;

/**
 * Class Endpoint
 *
 * @category    Class
 * @package     Twlve\Gojek\Meta
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class Endpoint
{
    /**
     * Base url
     */
    const BASE_ENDPOINT     = 'https://api.gojekapi.com/';

    /**
     * Authentication
     */
    const LOGIN_PHONE       = 'v3/customers/login_with_phone';
    const LOGIN_EMAIL       = 'v3/customers/login_with_email';
    const LOGIN_AUTH        = 'v3/customers/token';
    const LOGOUT            = 'v3/auth/token';

    /**
     * Customer
     */
    const CUSTOMER          = 'gojek/v2/customer';
    const CUSTOMER_UPDATE   = 'gojek/v2/customer/edit/v2';

    /**
     * Gopay
     */
    const GOPAY_DETAIL      = 'wallet/profile/detailed';
    const GOPAY_WALLETCODE  = 'wallet/qr-code';
    const GOPAY_TRANSFER    = 'v2/fund/transfer';
}
