<?php
/**
 * Created by PhpStorm.
 * User: Gen
 * Date: 13.5.2016 г.
 * Time: 18:38
 */

namespace Gentor\Tapfiliate\Reports;

use Gentor\Tapfiliate\Reports;

class Affiliate
{
    protected $url = 'affiliate';
    protected $params = [
        'sort_by' => 'title',
        'sort_direction' => 'ASC',
    ];
    protected $affiliate_id;


    public function __construct(Reports $reports, $affiliate_id = null)
    {
        $this->reports = $reports;
        $this->affiliate_id = $affiliate_id;
        $this->url = $reports->url . '/' . $this->url;
        $this->params['date_from'] = $this->reports->date_from;
    }

    public function totals()
    {
        $this->params = array_merge($this->params, [
            'date_to' => date('Y-m-d'),
            'filter_1_affiliate' => $this->affiliate_id,
            'filter_2_status' => 'all',
        ]);

        return $this->reports->api->get($this->url, $this->params);
    }

    public function totalsPending()
    {
        $this->params = array_merge($this->params, [
            'date_to' => date('Y-m-d'),
            'filter_1_affiliate' => $this->affiliate_id,
            'filter_2_status' => 'pending',
        ]);

        return $this->reports->api->get($this->url, $this->params);
    }

    public function totalsApproved()
    {
        $this->params = array_merge($this->params, [
            'date_to' => date('Y-m-d'),
            'filter_1_affiliate' => $this->affiliate_id,
            'filter_2_status' => 'approved',
        ]);

        return $this->reports->api->get($this->url, $this->params);
    }

    public function totalsDisapproved()
    {
        $this->params = array_merge($this->params, [
            'date_to' => date('Y-m-d'),
            'filter_1_affiliate' => $this->affiliate_id,
            'filter_2_status' => 'disapproved',
        ]);

        return $this->reports->api->get($this->url, $this->params);
    }

}