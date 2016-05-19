<?php
/**
 * Created by PhpStorm.
 * User: Gen
 * Date: 13.5.2016 г.
 * Time: 18:38
 */

namespace Gentor\Tapfiliate;


class Commissions
{
    protected $url = 'commissions';
    protected $commission_id;

    public function __construct(Tapfiliate $api, $commission_id = null)
    {
        $this->api = $api;
        $this->commission_id = $commission_id;
    }

    public function get()
    {
        return $this->api->get($this->url . "/$this->commission_id", []);
    }

    public function update($amount, $comment = null)
    {
        return $this->api->put($this->url . "/$this->commission_id", [
            'amount' => $amount,
            'comment' => $comment,
        ]);
    }

    public function approve()
    {
        return $this->api->put($this->url . "/$this->commission_id/approval", []);
    }

    public function disapprove()
    {
        return $this->api->delete($this->url . "/$this->commission_id/approval", []);
    }

}