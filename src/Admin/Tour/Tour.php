<?php
namespace AbcTravels\Admin\Tour;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\Admin\Destination\Destination;
use AbcTravels\Admin\Image\Image;

class Tour
{
    public static $table = DEF_TBL_TOURS;
    public static $tableDestinations = DEF_TBL_DESTINATIONS;
    public static $data = [];

    public static function addOrUpdateTour()
    {
        $allowedTags = getTextEditorAllowedTags();
        
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $title = stringToUpper(trim($_REQUEST['title']));
        $destinationId = trim($_REQUEST['destinationId']);
        $days = doTypeCastInt($_REQUEST['numberOfDays']);
        $price = doTypeCastDouble($_REQUEST['price']);
        $inclusions = strip_tags(stripslashes(trim($_REQUEST['inclusions'])), $allowedTags);
        $summary = strip_tags(stripslashes(trim($_REQUEST['summary'])), $allowedTags);
        $mapIframe = trim($_REQUEST['mapIframe']);
        $isSpecialPackage = isset($_REQUEST['specialPackage']) ? doTypeCastInt($_REQUEST['specialPackage']) : 0;

        $arItenaryDayTextareas = $arItenaryDayTitles = $arItenaryDayAcc = $arItenaryDayAccLink = [];
        $arItenaryDayMealPlans = $arItenaryDayTravelTimes = $arItenaryDayTransferModes = [];
        foreach($_REQUEST as $fieldId => $fieldValue)
        {
            $fieldValue = str_replace('~', '-', trim($fieldValue));
            $fieldValue = str_replace('|', ' ', $fieldValue);
            if (strpos($fieldId, 'itenaryDayTextareaId') !== false)
            {
                $arItenaryDayTextareas[] = $fieldId .'~'. $fieldValue;
            }
            if (strpos($fieldId, 'itenaryDayTitleId') !== false)
            {
                $arItenaryDayTitles[] = $fieldId .'~'. $fieldValue;
            }
            if (strpos($fieldId, 'itenaryDayAccId') !== false)
            {
                $arItenaryDayAcc[] = $fieldId .'~'. $fieldValue;
            }
            if (strpos($fieldId, 'itenaryDayAccLinkId') !== false)
            {
                $arItenaryDayAccLink[] = $fieldId .'~'. $fieldValue;
            }
            if (strpos($fieldId, 'itenaryDayMealPlanId') !== false)
            {
                $arItenaryDayMealPlans[] = $fieldId .'~'. $fieldValue;
            }
            if (strpos($fieldId, 'itenaryDayTravelTimeId') !== false)
            {
                $arItenaryDayTravelTimes[] = $fieldId .'~'. $fieldValue;
            }
            if (strpos($fieldId, 'itenaryDayTransferModeId') !== false)
            {
                $arItenaryDayTransferModes[] = $fieldId .'~'. $fieldValue;
            }
        }
        $itenaryDayDetails = implode('|', $arItenaryDayTextareas);
        $itenaryDayTitles = implode('|', $arItenaryDayTitles);
        $itenaryDayAcc = implode('|', $arItenaryDayAcc);
        $itenaryDayAccLink = implode('|', $arItenaryDayAccLink);
        $itenaryDayMealPlans = implode('|', $arItenaryDayMealPlans);
        $itenaryDayTravelTimes = implode('|', $arItenaryDayTravelTimes);
        $itenaryDayTransferModes = implode('|', $arItenaryDayTransferModes);

        if (Crud::checkDuplicate(self::$table, 'title', $title, $id))
        {
            throw new Exception('Record with this title already exists');
        }

        $fileName = '';
        $imgFileSize = $_FILES['featuredImg']['size'];
        if ($imgFileSize > 0)
        {
            //upload image
            $fileName = Image::uploadImage();
        }

        $shortName = strtolower(str_replace(' ', '_', $title));

        $data = [
            'title' => $title,
            'short_name' => $shortName,
            'destination_id' => $destinationId,
            'days' => $days,
            'price' => $price,
            'itenary_title' => $itenaryDayTitles,
            'itenary_details' => $itenaryDayDetails,
            'itenary_accomodation' => $itenaryDayAcc,
            'itenary_accomodation_link' => $itenaryDayAccLink,
            'itenary_meal_plan' => $itenaryDayMealPlans,
            'itenary_travel_time' => $itenaryDayTravelTimes,
            'itenary_transfer_mode' => $itenaryDayTransferModes,
            'inclusions' => $inclusions,
            'summary' => $summary,
            'special_package' => $isSpecialPackage
        ];
        if ($mapIframe != '')
        {
            $data['map'] = $mapIframe;
        }
        if ($fileName != '')
        {
            $data['img'] = $fileName;
        }
        if ($id == '')
        {
            $id = getNewId();
            $data['id'] = $id;
            $data['cdate'] = time();
            Crud::insert(self::$table, $data);

            self::$data = [
                'status' => true,
                'data' => [
                    'url' => "app/tour?id=$id"
                ]
            ];
        }
        else
        {
            $data['mdate'] = time();
            Crud::update(
                self::$table,
                $data,
                ['id' => $id]
            );
        }
    }
    
    public static function updateTour()
    {
        $id = $_REQUEST['id'];
        $name = stringToUpper(trim($_REQUEST['name']));

        if (Crud::checkDuplicate(self::$table, 'name', $name, $id))
        {
            throw new Exception('Record with this name already exists');
        }

        $data = [
            'name' => $name,
            'mdate' => time()
        ];
        Crud::update(
            self::$table,
            $data,
            ['id' => $id]
        );
        self::$data = ['msg' => 'Record updated successfully'];
    }

    public static function getTours($arFields=['*'], $typeId='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        $order = 'cdate DESC';
        if ($typeId == 'dropdown')
        {
            $order = 'name ASC';
        }
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => ['deleted' => 0],
                'return_type' => 'all',
                'order' => $order
            ]
        );
    }

    public static function getToursList()
    {
        $rs = self::getTours(['id, title, destination_id, days, price, img, cdate, mdate']);
        if(count($rs) > 0)
        {
            $rows = [];
            $arDestinations = Destination::getDestinationsArray();
            $sn = 1;
            $rootPath = DEF_ROOT_PATH;
            foreach($rs as $r)
            {
                $id = $r['id'];
                $img = $r['img'];
                $imgPath = '';
                if ($img != '')
                {
                    $imgPath = <<<EOQ
                    <img src="{$rootPath}/images/tour/{$r['img']}" class="adminTableImg">
EOQ;
                }

                $row = [
                    'sn' => $sn,
                    'title' => $r['title'],
                    'destination' => $arDestinations[$r['destination_id']],
                    'days' => $r['days'],
                    'price' => doNumberFormat($r['price']),
                    'img' => $imgPath,
                    'cdate' => getFormattedDate($r['cdate']),
                    'mdate' => getFormattedDate($r['mdate'])
                ];
                $row['edit'] = <<<EOQ
                <a href="app/tour?id={$id}" class="btn btn-primary btn-rounded btn-icon">Edit</a>
EOQ;
                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteTour('{$id}')">
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

    public static function deleteTour()
    {
        $id = $_REQUEST['id'];

        //Crud::delete(self::$table, ['id' => $id]);
        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
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

    public static function getTourItenaryFormData($rs)
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
        $arFieldId = explode('Id', $id);
        return [
            'itenaryDay' => $count,
            'itenaryDayCount' => doTypeCastInt(end($arFieldId)),
            'content' => $output
        ];
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
        
        $itenaryDayRowId = 'itenaryDayRowId'.$day;
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

        return <<<EOQ
        <div id="{$itenaryDayRowId}">
        <div class="form-group">
        <label for="{$itenaryDayTitleId}">Title</label>
        <input type="text" id="{$itenaryDayTitleId}" name="{$itenaryDayTitleId}" class="form-control" placeholder="e.g. Day {$day}" value="{$itenaryDayTitle}">
        </div>
        <div class="form-group">
        <label for="{$itenaryDayTextareaId}">Details</label>
        <textarea class="form-control" name="{$itenaryDayTextareaId}" id="{$itenaryDayTextareaId}" cols="30" rows="10">{$itenaryDayTextarea}</textarea>
        </div>
        <div class="form-group">
        <label for="{$itenaryDayAccId}">Accomodation</label>
        <input type="text" id="{$itenaryDayAccId}" name="{$itenaryDayAccId}" class="form-control" value="{$itenaryDayAcc}">
        </div>
        <div class="form-group">
        <label for="{$itenaryDayAccLinkId}">Accomodation Link</label>
        <input type="text" id="{$itenaryDayAccLinkId}" name="{$itenaryDayAccLinkId}" class="form-control" value="{$itenaryDayAccLink}">
        </div>
        <div class="form-group">
        <label for="{$itenaryDayMealPlanId}">Meal Plan</label>
        <input type="text" id="{$itenaryDayMealPlanId}" name="{$itenaryDayMealPlanId}" class="form-control" value="{$itenaryDayMealPlan}">
        </div>
        <div class="form-group">
        <label for="{$itenaryDayTravelTimeId}">Travel Time</label>
        <input type="text" id="{$itenaryDayTravelTimeId}" name="{$itenaryDayTravelTimeId}" class="form-control" value="{$itenaryDayTravelTime}">
        </div>
        <div class="form-group">
        <label for="{$itenaryDayTransferModeId}">Transfer Mode</label>
        <input type="text" id="{$itenaryDayTransferModeId}" name="{$itenaryDayTransferModeId}" class="form-control" value="{$itenaryDayTransferMode}">
        </div>
        <button class="btn btn-danger btn-sm mb-1" onclick="deleteItenaryDay({$day})"><i class="fas fa-trash"></i> Delete Row</button>
        <div class="progress progress-xxs mb-3">
        <div class="progress-bar progress-bar-danger bg-danger progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
        <span class="sr-only">60% Complete (warning)</span>
        </div>
        </div>
        </div>
EOQ;
    }
}