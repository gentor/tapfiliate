<?php
/**
 * Created by PhpStorm.
 * User: Gen
 * Date: 13.5.2016 г.
 * Time: 18:38
 */

namespace Gentor\Tapfiliate;


class Affiliates
{
    protected $url = 'affiliates';
    protected $affiliate_id;

    public function __construct(Tapfiliate $api, $affiliate_id = null)
    {
        $this->api = $api;
        $this->affiliate_id = $affiliate_id;
    }

    public function create($params)
    {
        return $this->api->post($this->url, $params);
    }

    public function setMetaData($data)
    {
        return $this->api->post($this->url . "/$this->affiliate_id/meta-data", $data);
    }

}