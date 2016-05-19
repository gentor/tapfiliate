<?php
/**
 * Created by PhpStorm.
 * User: Gen
 * Date: 13.5.2016 г.
 * Time: 18:38
 */

namespace Gentor\Tapfiliate;

class Reports
{
    public $url = 'reports';
    public $date_from = '2016-01-01';

    public function __construct(Tapfiliate $api, $date_from = null)
    {
        $this->api = $api;
        if ($date_from) {
            $this->date_from = $date_from;
        }
    }

    public function affiliate($affiliate_id = null)
    {
        return new Reports\Affiliate($this, $affiliate_id);
    }

}