<?php

/**
 * CustomerResponse.php
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek\Response;

use stdClass;

/**
 * Class CustomerResponse
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class CustomerResponse
{
    private $success;
    private $errorMessage;
    private $data;

    public function __construct($response)
    {
        $this->success      = isset($response->status) ? (strtolower($response->status) == 'ok' ? true : false) : false;
        $this->errorMessage = isset($response->error_messages) ? (count($response->error_messages) == 0 ? null : $response->error_messages[0]) : null;
        $this->data         = isset($response->customer) ? $response->customer : null;
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
