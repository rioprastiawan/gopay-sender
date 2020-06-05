<?php

/**
 * DefaultResponse.php
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek\Response;

/**
 * Class DefaultResponse
 *
 * @category    Class
 * @package     Twlve\Gojek\Response
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class DefaultResponse
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getResult()
    {
        return $this->response;
    }
}
