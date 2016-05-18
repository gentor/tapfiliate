<?php
/**
 * Created by PhpStorm.
 * User: Gen
 * Date: 13.5.2016 г.
 * Time: 18:38
 */

namespace Gentor\Tapfiliate;


class Programs
{
    protected $url = 'programs';
    protected $program_id;

    public function __construct(Tapfiliate $api, $program_id = null)
    {
        $this->api = $api;
        $this->program_id = $program_id;
    }

    public function addAffiliate($affiliate_id)
    {
        return $this->api->post($this->url . "/$this->program_id/affiliates", [
            'affiliate' => [
                'id' => $affiliate_id
            ]
        ]);
    }

}