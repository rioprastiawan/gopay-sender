<?php

/**
 * Curl.php
 *
 * @category    Class
 * @package     Twlve\Gojek\Http
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */

namespace Twlve\Gojek\Http;

use Twlve\Gojek\HTTPClient;
use Twlve\Gojek\Response;

/**
 * Class Curl
 *
 * @category    Class
 * @package     Twlve\Gojek\Http
 * @author      Rio Prastiawan <rioprastiawan19@gmail.com>
 * @license     https://opensource.org/licenses/MIT MIT License
 */
class Curl implements HTTPClient
{
    /**
     * curl init
     *
     * @var string
     */
    private $curl;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    private function _getHeaders($headers)
    {
        $result = [];

        foreach ($headers as $key => $val) {
            array_push($result, $key . ': ' . $val);
        }

        return $result;
    }

    /**
     * send get request
     *
     * @param  string                $url
     * @param  array                 $data
     * @param  array                 $headers
     * @return \Twlve\Gojek\Response
     */
    public function get($url, $data, $headers)
    {
        $headers['Content-Type'] =  'application/json;charset=UTF-8';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_getHeaders($headers));

        $result     = curl_exec($this->curl);
        $httpcode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return new Response($result, $httpcode, $url);
    }

    /**
     * send post request
     *
     * @param  string                $url
     * @param  array                 $data
     * @param  array                 $headers
     * @return \Twlve\Gojek\Response
     */
    public function post($url, $data, $headers)
    {
        $headers['Content-Type'] =  'application/json;charset=UTF-8';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_getHeaders($headers));

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));

        $result     = curl_exec($this->curl);
        $httpcode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return new Response($result, $httpcode, $url);
    }

    /**
     * send patch request
     *
     * @param  string                $url
     * @param  array                 $data
     * @param  array                 $headers
     * @return \Twlve\Gojek\Response
     */
    public function patch($url, $data, $headers)
    {
        $headers['Content-Type'] =  'application/json;charset=UTF-8';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_getHeaders($headers));

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));

        $result     = curl_exec($this->curl);
        $httpcode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return new Response($result, $httpcode, $url);
    }

    /**
     * send put request
     *
     * @param  string                $url
     * @param  array                 $data
     * @param  array                 $headers
     * @return \Twlve\Gojek\Response
     */
    public function put($url, $data, $headers)
    {
        $headers['Content-Type'] =  'application/json;charset=UTF-8';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_getHeaders($headers));

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($data));

        $result     = curl_exec($this->curl);
        $httpcode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return new Response($result, $httpcode, $url);
    }

    /**
     * send delete request
     *
     * @param  string                $url
     * @param  array                 $data
     * @param  array                 $headers
     * @return \Twlve\Gojek\Response
     */
    public function delete($url, $data, $headers)
    {
        $headers['Content-Type'] = 'application/json;charset=UTF-8';

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, false);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->_getHeaders($headers));

        $result     = curl_exec($this->curl);
        $httpcode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return new Response($result, $httpcode, $url);
    }
}
