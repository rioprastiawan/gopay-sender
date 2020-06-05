<?php

/**
 * CustomerUpdateResponse.php
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek\Response;

use stdClass;

/**
 * Class CustomerUpdateResponse
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class CustomerUpdateResponse
{
    private $success;
    private $errorMessage;
    private $data;

    public function __construct($response)
    {
        $this->success      = isset($response->status) ? (strtolower($response->status) == 'ok' ? true : false) : false;
        $this->errorMessage = isset($response->message) ? (strtolower($response->message) == 'ok' ? null : $response->message) : null;
        $this->data         = null;
    }

    public function getResult()
    {
        $result                 = new stdClass;
        $result->success        = $this->success;
        $result->error_message  = $this->errorMessage;
        $result->data           = $this->data;

        return $result;
    }
}
