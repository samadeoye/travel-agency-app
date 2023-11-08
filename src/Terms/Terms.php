<?php
namespace AbcTravels\Terms;

use AbcTravels\Crud\Crud;

class terms
{
    public static $table = DEF_TBL_TERMS;

    public static function updateTerms()
    {
        $privacyPolicy = trim($_REQUEST['privacyPolicy']);
        $taxiBookings = trim($_REQUEST['taxiBookings']);
        $trainReservations = trim($_REQUEST['trainReservations']);
        $safariReservations = trim($_REQUEST['safariReservations']);
        $tourReservations = trim($_REQUEST['tourReservations']);

        $data = [
            'privacy_policy' => $privacyPolicy,
            'taxi_bookings' => $taxiBookings,
            'train_reservations' => $trainReservations,
            'safari_reservations' => $safariReservations,
            'tour_reservations' => $tourReservations
        ];

        Crud::update(
            self::$table,
            $data,
            []
        );
    }

    public static function getTerms($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        return Crud::select(
            self::$table,
            [
                'columns' => $fields
            ]
        );
    }
}