<?php

/**
 * GopayTransferResponse.php
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek\Response;

use stdClass;

/**
 * Class GopayTransferResponse
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class GopayTransferResponse
{
    private $success;
    private $errorMessage;
    private $data;

    public function __construct($response)
    {
        $this->success      = isset($response->success) ? ($response->success ? true : false) : false;
        $this->errorMessage = isset($response->errors) ? (count($response->errors) == 0 ? null : $response->errors[0]->message) : null;
        $this->data         = isset($response->data) ? $response->data : null;
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
