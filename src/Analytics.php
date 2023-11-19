<?php
namespace AbcTravels;

use AbcTravels\UserAnalytics\UserAnalytics;
use AbcTravels\Crud\Crud;

class Analytics
{
    static $table = DEF_TBL_ANALYTICS;
    static $data = [];

    public static function logUserAnalytics()
    {
        $objAnalytics = new UserAnalytics();
        $data = [
            'id' => getNewId(),
            'ip' => $objAnalytics->getIP(),
            'page_url' => $objAnalytics->getCurrentURL(),
            'browser' => $objAnalytics->getBrowser(),
            'language' => $objAnalytics->getLanguage(),
            'country_code' => $objAnalytics->getCountryCode(),
            'country_name' => $objAnalytics->getCountryName(),
            'region_code' => $objAnalytics->getRegionCode(),
            'region_name' => $objAnalytics->getRegionName(),
            'city' => $objAnalytics->getCity(),
            'zipcode' => $objAnalytics->getZipcode(),
            'timezone' => $objAnalytics->getTimeZone(),
            'latitude' => $objAnalytics->getLatitude(),
            'longitude' => $objAnalytics->getLongitude(),
            'cdate' => time()
        ];
        //print_r($data);exit;
        Crud::insert(
            self::$table,
            $data
        );
    }

    public static function getAnalytics($arFields=['*'], $groupBy='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'return_type' => 'all',
        ];
        if ($groupBy != '')
        {
            //FOR THE CHART
            $arFilter['where'] = [
                'expression' => 'country_name <> ""'
            ];
            $arFilter['group'] = $groupBy;
        }
        else
        {
            $arFilter['order'] = 'cdate DESC';
        }
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    public static function getAnalyticsList()
    {
        $rs = self::getAnalytics();
        if(count($rs) > 0)
        {
            $rows = [];
            foreach($rs as $r)
            {
                $url = $r['page_url'];
                $pageLink = <<<EOQ
                <a href="{$url}">{$url}</a>
EOQ;
                $row = [
                    'cdate' => getFormattedDate($r['cdate']),
                    'ip' => $r['ip'],
                    'page' => $pageLink,
                    'country_name' => $r['country_name'],
                    'country_code' => $r['country_code'],
                    'region_name' => $r['region_name'],
                    'region_code' => $r['region_code'],
                    'city' => $r['city'],
                    'timezone' => $r['timezone'],
                    'latitude' => $r['latitude'],
                    'longitude' => $r['longitude']
                ];

                $rows[] = $row;
            }
            $data = [
                'status' => true,
                'msg' => 'Records fetched successfully!',
                'data' => $rows
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg' => 'No record found!',
                'data' => []
            ];
        }
        self::$data = $data;
    }

    public static function getAnalyticsChartData()
    {
        $rs = self::getAnalytics(['country_name', 'COUNT(id) AS count'], 'country_name');
        $data = [
            'status' => true,
            'msg' => 'Records fetched successfully!',
            'data' => $rs
        ];
        self::$data = $data;
    }
}