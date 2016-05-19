<?php

namespace Gentor\Tapfiliate;

use Illuminate\Config\Repository;

class Tapfiliate
{

    public $ch;
    public $debug = false;
    /**
     * Illuminate config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    protected $api_key;

    protected $root = 'https://tapfiliate.com/api/1.4/';

    /**
     * Create a new Tapfiliate instance.
     *
     * @param  \Illuminate\Config\Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
        $this->api_key = $this->config->get('tapfiliate::api_key');
        $this->debug = $this->config->get('tapfiliate::debug');

        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Api-Key: ' . $this->api_key,
        ]);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Gentor-Tapfiliate-PHP/1.0.0');
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->config->get('tapfiliate::timeout'));
    }

    public function affiliates($affiliate_id = null)
    {
        return new Affiliates($this, $affiliate_id);
    }

    public function programs($program_id = null)
    {
        return new Programs($this, $program_id);
    }

    public function conversions()
    {
        return new Conversions($this);
    }

    public function reports($date_from = null)
    {
        return new Reports($this, $date_from);
    }

    public function __destruct()
    {
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
    }

    public function get($url, $params)
    {
        return $this->call($url, $params, 'get');
    }

    public function post($url, $params)
    {
        return $this->call($url, $params, 'post');
    }

    public function put($url, $params)
    {
        return $this->call($url, $params, 'put');
    }

    public function delete($url, $params)
    {
        return $this->call($url, $params, 'delete');
    }

    private function call($url, $params, $method = 'post')
    {
        $ch = $this->ch;
        $endpoint = $this->root . $url . '/';

        if (in_array($method, ['get'])) {
            $params = http_build_query($params);
            $endpoint .= '?' . $params;
        }

        if (in_array($method, ['post', 'put', 'delete'])) {
            $params = json_encode($params);
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        if (in_array($method, ['put', 'delete'])) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, $this->debug);

        $start = microtime(true);
        $this->log('Call to ' . $this->root . $url . ': ' . $params);
        if ($this->debug) {
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }

        $response_body = curl_exec($ch);

        $info = curl_getinfo($ch);
        $time = microtime(true) - $start;
        if ($this->debug) {
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }
        $this->log('Completed in ' . number_format($time * 1000, 2) . 'ms');
        $this->log('Got response: ' . $response_body);

        if (curl_error($ch)) {
            throw new \Exception("API call to $url failed: " . curl_error($ch));
        }

        if (floor($info['http_code'] / 100) >= 4) {
            $this->castError($response_body);
        }

        $result = json_decode($response_body, true);

        return $result;
    }

    public function castError($response)
    {
        if (is_array($result = json_decode($response, true))) {
            if (!empty($result['errors'])) {
                throw new TapfiliateError($result['errors'][0]['message']);
            }
        } else {
            $result = $response;
        }

        if ($result == "You exceeded the rate limit") {
            throw new TapfiliateRateLimit($result);
        }

        throw new TapfiliateError('We received an unexpected error: ' . $result);
    }

    public function log($msg)
    {
        if ($this->debug) {
            error_log($msg);
        }
    }

}