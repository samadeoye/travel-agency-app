<?php
namespace AbcTravels\Admin\Dashboard;

use AbcTravels\Crud\Crud;

class Dashboard
{
    protected static $tableDestinations = DEF_TBL_DESTINATIONS;
    protected static $tableTours = DEF_TBL_TOURS;
    protected static $tableSubmissions = DEF_TBL_SUBMISSIONS;

    public static function getDashboardData()
    {
        $numDestinations = $numTours = $numSubmissions = 0;

        //destinations
        $numDestinations = self::getDashboardCommonCount(self::$tableDestinations);
        //tours
        $numTours = self::getDashboardCommonCount(self::$tableTours);
        //submissions
        $numSubmissions = self::getDashboardCommonCount(self::$tableSubmissions);

        return [
            'numDestinations' => $numDestinations,
            'numTours' => $numTours,
            'numSubmissions' => $numSubmissions
        ];
    }

    protected static function getDashboardCommonCount($table)
    {
        $rs = Crud::select(
            $table,
            [
                'columns' => 'COUNT(id) AS num',
                'where' => [
                    'deleted' => 0
                ]
            ]
        );
        if ($rs)
        {
            return $rs['num'];
        }
        return 0;
    }
}