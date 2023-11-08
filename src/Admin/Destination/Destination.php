<?php
namespace AbcTravels\Admin\Destination;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\Admin\Image\Image;

class Destination
{
    public static $table = DEF_TBL_DESTINATIONS;
    public static $tableTours = DEF_TBL_TOURS;
    public static $data = [];
    static $appPerPage = 15;

    public static function addDestination()
    {
        $allowedTags = getTextEditorAllowedTags();

        $name = stringToUpper(trim($_REQUEST['name']));
        $faqs = strip_tags(stripslashes(trim($_REQUEST['faqs'])), $allowedTags);

        if (Crud::checkDuplicate(self::$table, 'name', $name))
        {
            throw new Exception('Record with this name already exists.');
        }

        $fileName = '';
        $imgFileSize = $_FILES['featuredImg']['size'];
        if ($imgFileSize > 0)
        {
            //upload image
            Image::$directory = 'images/destination/';//'assets/img/destination/';
            $fileName = Image::uploadImage();
        }

        $shortName = strtolower(str_replace(' ', '_', $name));

        $data = [
            'id' => getNewId(),
            'name' => $name,
            'short_name' => $shortName,
            'faqs' => $faqs,
            'cdate' => time()
        ];
        if ($fileName != '')
        {
            $data['img'] = $fileName;
        }
        Crud::insert(self::$table, $data);
    }
    
    public static function updateDestination()
    {
        $allowedTags = getTextEditorAllowedTags();
        
        $id = $_REQUEST['id'];
        $name = stringToUpper(trim($_REQUEST['name']));
        $faqs = strip_tags(stripslashes(trim($_REQUEST['faqs'])), $allowedTags);

        if (Crud::checkDuplicate(self::$table, 'name', $name, $id))
        {
            throw new Exception('Record with this name already exists');
        }

        $fileName = '';
        $imgFileSize = $_FILES['featuredImg']['size'];
        if ($imgFileSize > 0)
        {
            //upload image
            Image::$directory = 'images/destination/';//'assets/img/destination/';
            $fileName = Image::uploadImage();
        }

        $shortName = strtolower(str_replace(' ', '_', $name));

        $data = [
            'name' => $name,
            'short_name' => $shortName,
            'faqs' => $faqs,
            'mdate' => time()
        ];
        if ($fileName != '')
        {
            $data['img'] = $fileName;
        }
        Crud::update(
            self::$table,
            $data,
            ['id' => $id]
        );
        self::$data = ['msg' => 'Record updated successfully'];
    }

    public static function getDestinations($arFields=['*'], $typeId='')
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

    public static function getDestinationsList()
    {
        $rs = self::getDestinations(['id, name, img, cdate, mdate']);
        if(count($rs) > 0)
        {
            $rows = [];
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
                    <img src="{$rootPath}/images/destination/{$r['img']}" class="adminTableImg">
EOQ;
                }

                $row = [
                    'sn' => $sn,
                    'name' => $r['name'],
                    'img' => $imgPath,
                    'cdate' => getFormattedDate($r['cdate']),
                    'mdate' => getFormattedDate($r['mdate'])
                ];
                $row['edit'] = <<<EOQ
                <button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editDestination('{$id}')">
                    Edit
                </button>
EOQ;
                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteDestination('{$id}')">
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

    public static function getDestinationsDropdownOptions($destinationId)
    {
        $options = '';
        $rs = self::getDestinations(['id, name'], 'dropdown');
        if (count($rs) > 0)
        {
            foreach($rs as $r)
            {
                $name = $r['name'];
                $id = $r['id'];
                $selected = '';
                if ($destinationId == $id)
                {
                    $selected = 'selected';
                }
                $options .= <<<EOQ
                <option value="{$id}" {$selected}>{$name}</option>
EOQ;
            }
        }
        return $options;
    }

    public static function deleteDestination()
    {
        $id = $_REQUEST['id'];

        //check if any tour is linked with this destination
        if (self::checkIfTourIsLinkedWithDestination($id))
        {
            throw new Exception('You cannot delete this destination as it is associated with a tour package');
        }

        //Crud::delete(self::$table, ['id' => $id]);
        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
        );
    }

    public static function checkIfTourIsLinkedWithDestination($destinationId)
    {
        $rs = Crud::select(
            self::$tableTours,
            [
                'columns' => 'COUNT(id) AS num',
                'where' => [
                    'destination_id' => $destinationId
                ]
            ]
        );
        if ($rs)
        {
            if ($rs['num'] > 0)
            {
                return true;
            }
        }
        return false;
    }

    public static function getDestination($id, $arFields=['*'])
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

    public static function getDestinationsArray()
    {
        $ar = [];
        $rs = self::getDestinations(['id', 'name']);
        if (count($rs) > 0)
        {
            foreach($rs as $r)
            {
                $ar[$r['id']] = $r['name'];
            }
        }
        return $ar;
    }
}