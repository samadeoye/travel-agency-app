<?php
namespace AbcTravels\Tour;

use AbcTravels\Crud\Crud;

class Tour
{
    public static $table = DEF_TBL_TOURS;
    public static $tableDestinations = DEF_TBL_DESTINATIONS;
    public static $data = [];
    static $appPerPage = 15;

    public static function getToursHomePage($arFields=['*'], $limit=0, $typeId='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        $order = 'cdate DESC';
        if ($typeId == 'dropdown')
        {
            $order = 'name ASC';
        }
        $arFilter = [
            'columns' => $fields,
            'where' => ['deleted' => 0],
            'return_type' => 'all',
            'order' => $order
        ];
        if (doTypeCastInt($limit) > 0)
        {
            $arFilter['limit'] = $limit;
        }
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    public static function getTour($id, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'id' => $id
                ]
            ]
        );
    }

    public static function getTours($destinationId='', $page=1, $arFields=['*'], $tourDuration=0, $isSpecialTours=false)
    {
        $fields = is_array($arFields) ? implode(', ', $arFields) : $arFields;

        $perPage = self::$appPerPage;
        if($page <= 1)
        {
            $limit = '0,'.$perPage;
        }
        else
        {
            $offset = doTypeCastDouble(($page - 1) * $perPage);
            $limit = $offset.','.$perPage;
        }
        $specialTours = 0;
        if ($isSpecialTours)
        {
            $specialTours = 1;
        }
        $arWhere = [
            'special_package' => $specialTours,
            'deleted' => 0
        ];
        if ($destinationId != '')
        {
            $arWhere['destination_id'] = $destinationId;
        }
        if (doTypeCastInt($tourDuration) > 0)
        {
            $arWhere['days'] = $tourDuration;
        }
        $data = [
            'columns' => $fields,
            'where' => $arWhere,
            'return_type' => 'all',
            'order' => 'days ASC',
        ];
        if($limit != '')
        {
            $data['limit'] = $limit;
        }

        return Crud::select(
            self::$table,
            $data
        );
    }

    public static function getToursContent($destinationId, $page=1, $tourDuration=0)
    {
        $rs = self::getTours($destinationId, $page, ['*'], $tourDuration);
        if (count($rs) == 0)
        {
            return 'No tour found';
        }
        $output = '';
        foreach($rs as $r)
        {
            $img = !empty($r['img']) ? $r['img'] : 'boxed-bg.png';
            $imgPath = 'images/tour/'.$img;
            $shortName = $r['short_name'];
            $title = stringToTitle($r['title']);
            $output .= <<<EOQ
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                <div class="location-card style-2">
                    <div class="image-wrapper">
                        <div class="image-inner tourImgWrapper">
                            <a href="tour-details?package={$shortName}"><img src="{$imgPath}" alt="{$title}"></a>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="content-inner">
                            <span class="content-title"><a href="tour-details?package={$shortName}" class="font-size-20">{$title}</a></span>
                            <div class="btn-wrapper">
                                <a href="tour-details?package={$shortName}" class="theme-btn theme-btn-padding-2">More Information <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOQ;
        }
        $output .= <<<EOQ
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
            <div class="location-card style-2">
                <div class="image-wrapper">
                    <div class="image-inner tourImgWrapper">
                        <div class="customize-trip">
                            <h5>Customize a Trip</h5>
                            <p>Let us know your personalized tour preferences we will give priority with ease to ensure your satisfaction throughout the journey.</p>
                            <a href="javascript:;" onclick="openCustomizeTripModal()" class="theme-btn bg-success theme-btn-padding-2">Cick here <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
EOQ;

        return $output;
    }

    public static function getToursTotal($destinationId='', $tourDuration=0, $isSpecialTours=false)
    {
        $specialTours = 0;
        if ($isSpecialTours)
        {
            $specialTours = 1;
        }
        $arWhere = [
            'special_package' => $specialTours,
            'deleted' => 0
        ];
        if ($destinationId != '')
        {
            $arWhere['destination_id'] = $destinationId;
        }
        if (doTypeCastInt($tourDuration) > 0)
        {
            $arWher0e['days'] = $tourDuration;
        }
        $rsTotal = Crud::select(
            self::$table,
            [
                'columns' => 'COUNT(id) AS total',
                'where' => $arWhere,
            ]
        );
        return doTypeCastDouble($rsTotal['total']);
    }

    public static function getToursPagination($destinationId, $page=1, $tourDuration=0)
    {
        $total = self::getToursTotal($destinationId, $tourDuration);
        if ($total == 0)
        {
            return '';
        }
        //get last page
        $perPage = self::$appPerPage;
        $lastPage = ceil($total / $perPage);
        
        $prev = $page - 1;
        $next = $page + 1;

        $prevOnClick = "showPagination('{$prev}')";
        $nextOnClick = "showPagination('{$next}')";
        if ($page <= 1)
        {
            $prevOnClick = '';
        }
        if ($page >= $lastPage)
        {
            $nextOnClick = '';
        }

        $output = <<<EOQ
        <div class="basic-pagination" id="appToursPagination">
            <input type="hidden" name="currentPage" id="currentPage" value="{$page}">
            <ul class="justify-content-center">
            <li><a class="page-numbers" onclick="{$prevOnClick}"><i class="fa fa-arrow-left"></i></a></li>
EOQ;
        for($i = 1; $i <= $lastPage; $i++)
        {
            $current = '';
            $onClick = "showPagination('{$i}')";
            if ($i == $page)
            {
                $current = 'current';
                $onClick = '';
            }
            $output .= <<<EOQ
            <li><a class="page-numbers {$current}" onclick="{$onClick}">{$i}</a></li>
EOQ;
        }
        $output .= <<<EOQ
            <li><a class="page-numbers" onclick="{$nextOnClick}"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
EOQ;

        return $output;
    }

    public static function getAppToursPaginationData()
    {
        $destinationId = doTypeCastInt($_REQUEST['destinationId']);
        $tourDuration = doTypeCastInt($_REQUEST['tourDuration']);
        $page = doTypeCastInt($_REQUEST['page']);
        $pagination = self::getToursPagination($destinationId, $page, $tourDuration);
        $list = self::getToursContent($destinationId, $page, $tourDuration);

        $data = [
            'pagination' => $pagination,
            'list' => $list
        ];

        self::$data = [
            'status' => true,
            'data' => $data
        ];
    }

    public static function getTourInfoByShortName($shortName, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'short_name' => $shortName
                ]
        ]);
    }

    public static function getRelatedTours($destinationId, $id)
    {
        return Crud::select(
            self::$table,
            [
                'columns' => 'title, short_name',
                'where' => [
                    'destination_id' => $destinationId,
                    'expression' => "id <> '{$id}'",
                    'special_package' => 0,
                    'deleted' => 0
                ],
                'return_type' => 'all',
                'order' => 'days ASC',
                'limit' => 10
            ]
        );
    }

    public static function getItenaryDisplay($rs)
    {
        $output = $id = '';
        $arItenaryTitles = $arItenaryAcc = $arItenaryAccLink = $arItenaryMealPlans = $arItenaryTravelTimes = $arItenaryTransferModes = $arItenaryDetails = [];
        $count = 0;
        foreach($rs as $fieldId => $fieldValue)
        {
            $arFieldValue = explode('|', $fieldValue);
            $count = count($arFieldValue);
            foreach($arFieldValue as $field)
            {
                $arField = explode('~', $field);
                $id = $arField[0];
                $value = $arField[1];
                switch($fieldId)
                {
                    case 'itenary_title':
                        $arItenaryTitles[$id] = $value;
                    break;
                    case 'itenary_accomodation':
                        $arItenaryAcc[$id] = $value;
                    break;
                    case 'itenary_accomodation_link':
                        $arItenaryAccLink[$id] = $value;
                    break;
                    case 'itenary_meal_plan':
                        $arItenaryMealPlans[$id] = $value;
                    break;
                    case 'itenary_travel_time':
                        $arItenaryTravelTimes[$id] = $value;
                    break;
                    case 'itenary_transfer_mode':
                        $arItenaryTransferModes[$id] = $value;
                    break;
                    case 'itenary_details':
                        $arItenaryDetails[$id] = $value;
                    break;
                }
            }
        }
        $arParams = [
            'arItenaryTitles' => $arItenaryTitles,
            'arItenaryAcc' => $arItenaryAcc,
            'arItenaryAccLink' => $arItenaryAccLink,
            'arItenaryMealPlans' => $arItenaryMealPlans,
            'arItenaryTravelTimes' => $arItenaryTravelTimes,
            'arItenaryTransferModes' => $arItenaryTransferModes,
            'arItenaryDetails' => $arItenaryDetails
        ];
        for($day=1; $day<=$count; $day++)
        {
            $output .= self::getTourIntenaryHtmlContent($day, $arParams);
        }
        return $output;
    }

    public static function getTourIntenaryHtmlContent($day, $arParams)
    {
        $arItenaryTitles = $arParams['arItenaryTitles'];
        $arItenaryAcc = $arParams['arItenaryAcc'];
        $arItenaryAccLink = $arParams['arItenaryAccLink'];
        $arItenaryMealPlans = $arParams['arItenaryMealPlans'];
        $arItenaryTravelTimes = $arParams['arItenaryTravelTimes'];
        $arItenaryTransferModes = $arParams['arItenaryTransferModes'];
        $arItenaryDetails = $arParams['arItenaryDetails'];
        
        $itenaryDayTextareaId = 'itenaryDayTextareaId'.$day;
        $itenaryDayTitleId = 'itenaryDayTitleId'.$day;
        $itenaryDayAccId = 'itenaryDayAccId'.$day;
        $itenaryDayAccLinkId = 'itenaryDayAccLinkId'.$day;
        $itenaryDayMealPlanId = 'itenaryDayMealPlanId'.$day;
        $itenaryDayTravelTimeId = 'itenaryDayTravelTimeId'.$day;
        $itenaryDayTransferModeId = 'itenaryDayTransferModeId'.$day;

        $itenaryDayTitle = $arItenaryTitles[$itenaryDayTitleId];
        $itenaryDayAcc = $arItenaryAcc[$itenaryDayAccId];
        $itenaryDayAccLink = $arItenaryAccLink[$itenaryDayAccLinkId];
        $itenaryDayMealPlan = $arItenaryMealPlans[$itenaryDayMealPlanId];
        $itenaryDayTravelTime = $arItenaryTravelTimes[$itenaryDayTravelTimeId];
        $itenaryDayTransferMode = $arItenaryTransferModes[$itenaryDayTransferModeId];
        $itenaryDayTextarea = $arItenaryDetails[$itenaryDayTextareaId];
        //$itenaryDayTextarea = str_replace('src="assets/', 'src="'.DEF_CORE_PATH.'assets/', $itenaryDayTextarea);

        if ($itenaryDayAccLink != '')
        {
            $itenaryDayAcc = <<<EOQ
            <a href="{$itenaryDayAccLink}" target="_blank">{$itenaryDayAcc}</a>
EOQ;
        }

        if ($itenaryDayAcc != '')
        {
            $itenaryDayAcc = <<<EOQ
            <li><i class="fa-solid fa-bed p-2"></i> <span class="fw-bold">Accomodation:</span> {$itenaryDayAcc} </li>
EOQ;
        }
        if ($itenaryDayMealPlan != '')
        {
            $itenaryDayMealPlan = <<<EOQ
            <li><i class="fa-solid fa-utensils p-2"></i> <span class="fw-bold">Meal Plan:</span> {$itenaryDayMealPlan} </li>
EOQ;
        }
        if ($itenaryDayTravelTime != '')
        {
            $itenaryDayTravelTime = <<<EOQ
            <li><i class="fa-regular fa-clock p-2"></i> <span class="fw-bold">Travel Time:</span> {$itenaryDayTravelTime} </li>
EOQ;
        }
        if ($itenaryDayTransferMode != '')
        {
            $itenaryDayTransferMode = <<<EOQ
            <li><i class="fa-solid fa-car p-2"></i> <span class="fw-bold">Transfer Mode:</span> {$itenaryDayTransferMode} </li>
EOQ;
        }

        $itenaryTripInfo = '';
        if ($itenaryDayAcc != '' || $itenaryDayMealPlan != '' || $itenaryDayTravelTime != '' || $itenaryDayTransferMode != '')
        {
            $itenaryTripInfo = <<<EOQ
            <ul class="list-unstyled">
                {$itenaryDayAcc}
                {$itenaryDayMealPlan}
                {$itenaryDayTravelTime}
                {$itenaryDayTransferMode}
            </ul>
EOQ;
        }

        return <<<EOQ
        <div class="accordion-list-item">
            <div id="heading{$day}">
                <div class="accordion-head"  role="button" data-bs-toggle="collapse" data-bs-target="#collapse{$day}" aria-expanded="true" aria-controls="collapse{$day}">
                    <h3 class="accordion-title fw-bold text-black">{$itenaryDayTitle}</h3>
                </div>
            </div>
            <div id="collapse{$day}" role="button" class="accordion-collapse collapse show" aria-labelledby="heading{$day}" data-bs-parent="#appointmentAreaStyle1FAQ">
                <div class="accordion-item-body">
                    {$itenaryDayTextarea}
                    {$itenaryTripInfo}
                </div>
            </div>
        </div>
EOQ;
    }

    public static function getSpecialToursContent($destinationId, $page=1, $tourDuration=0)
    {
        $rs = self::getTours($destinationId, $page, ['*'], $tourDuration, true);
        if (count($rs) == 0)
        {
            return 'No special tours found';
        }
        $output = '';
        foreach($rs as $r)
        {
            $img = !empty($r['img']) ? $r['img'] : 'boxed-bg.png';
            $imgPath = 'images/tour/'.$img;
            $shortName = $r['short_name'];
            $title = stringToTitle($r['title']);
            $days = doTypeCastInt($r['days']);
            $output .= <<<EOQ
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                <div class="location-card style-2">
                    <div class="image-wrapper">
                        <div class="image-inner tourImgWrapper">
                            <a href="tour-details?package={$shortName}"><img src="{$imgPath}" alt="{$title}"></a>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="content-inner">
                            <span class="content-title"><a href="tour-details?package={$shortName}" class="font-size-20">{$title}</a></span>
                            <span class="badge bg-theme"><i class="fa-solid fa-clock"></i> {$days} Days</span>
                            <div class="btn-wrapper">
                                <a href="tour-details?package={$shortName}" class="theme-btn theme-btn-padding-2">More Information <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOQ;
        }

        return $output;
    }

    public static function getSpecialToursPagination($destinationId, $page=1, $tourDuration=0)
    {
        $total = self::getToursTotal($destinationId, $tourDuration, true);
        if ($total == 0)
        {
            return '';
        }
        //get last page
        $perPage = self::$appPerPage;
        $lastPage = ceil($total / $perPage);
        
        $prev = $page - 1;
        $next = $page + 1;

        $prevOnClick = "showSpecialPagination('{$prev}')";
        $nextOnClick = "showSpecialPagination('{$next}')";
        if ($page <= 1)
        {
            $prevOnClick = '';
        }
        if ($page >= $lastPage)
        {
            $nextOnClick = '';
        }

        //TODO: Change look => add days, etc
        $output = <<<EOQ
        <div class="basic-pagination" id="appSpecialToursPagination">
            <input type="hidden" name="currentSpecialPage" id="currentSpecialPage" value="{$page}">
            <ul class="justify-content-center">
            <li><a class="page-numbers" onclick="{$prevOnClick}"><i class="fa fa-arrow-left"></i></a></li>
EOQ;
        for($i = 1; $i <= $lastPage; $i++)
        {
            $current = '';
            $onClick = "showSpecialPagination('{$i}')";
            if ($i == $page)
            {
                $current = 'current';
                $onClick = '';
            }
            $output .= <<<EOQ
            <li><a class="page-numbers {$current}" onclick="{$onClick}">{$i}</a></li>
EOQ;
        }
        $output .= <<<EOQ
            <li><a class="page-numbers" onclick="{$nextOnClick}"><i class="fa fa-arrow-right"></i></a></li>
            </ul>
        </div>
EOQ;

        return $output;
    }

    public static function getAppSpecialToursPaginationData()
    {
        $destinationId = doTypeCastInt($_REQUEST['destinationId']);
        $tourDuration = doTypeCastInt($_REQUEST['tourDuration']);
        $page = doTypeCastInt($_REQUEST['page']);
        $pagination = self::getSpecialToursPagination($destinationId, $page, $tourDuration);
        $list = self::getSpecialToursContent($destinationId, $page, $tourDuration);

        $data = [
            'pagination' => $pagination,
            'list' => $list
        ];

        self::$data = [
            'status' => true,
            'data' => $data
        ];
    }
}