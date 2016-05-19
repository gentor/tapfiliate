<?php
/**
 * Created by PhpStorm.
 * User: Gen
 * Date: 13.5.2016 г.
 * Time: 18:38
 */

namespace Gentor\Tapfiliate;


class Conversions
{
    protected $url = 'conversions';

    public function __construct(Tapfiliate $api)
    {
        $this->api = $api;
    }

    public function create($params)
    {
        return $this->api->post($this->url, $params);
    }

}