<?php
namespace AbcTravels\Subscription;

use AbcTravels\Crud\Crud;
use Exception;

class Subscription
{
    protected static $table = DEF_TBL_SUBSCRIPTIONS;
    static $data = [];

    public static function addSubscription()
    {
        $email = trim($_REQUEST['email']);

        if (Crud::checkDuplicate(self::$table, 'email', $email))
        {
            throw new Exception('Email already added!');
        }

        $data = [
            'id' => getNewId(),
            'email' => $email,
            'cdate' => time()
        ];

        Crud::insert(
            self::$table,
            $data
        );
    }

    public static function getSubscriptions($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        $arFilter = [
            'columns' => $fields,
            'where' => ['deleted' => 0],
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    public static function getSubscriptionsList()
    {
        $rs = self::getSubscriptions(['id', 'email', 'cdate']);
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach($rs as $r)
            {
                $id = $r['id'];

                $row = [
                    'sn' => $sn,
                    'email' => $r['email'],
                    'cdate' => getFormattedDate($r['cdate'])
                ];

                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteSubscription('{$id}')">
                    Delete
                </button>
EOQ;
                $rows[] = $row;
                $sn++;
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

    public static function deleteSubscription()
    {
        $id = trim($_REQUEST['id']);

        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
        );
    }
}