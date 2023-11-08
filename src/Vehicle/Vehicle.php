<?php
namespace AbcTravels\Vehicle;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\Admin\Image\Image;

class Vehicle
{
    public static $table = DEF_TBL_VEHICLES;
    public static $data = [];
    static $appPerPage = 15;

    public static function addOrUpdateVehicle()
    {
        $name = stringToUpper(trim($_REQUEST['name']));
        $passengers = trim($_REQUEST['passengers']);
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if (Crud::checkDuplicate(self::$table, 'name', $name, $id))
        {
            throw new Exception('Record with this name already exists.');
        }

        $fileName = '';
        $imgFileSize = $_FILES['featuredImg']['size'];
        if ($imgFileSize > 0)
        {
            //upload image
            Image::$directory = 'images/vehicle/';
            $fileName = Image::uploadImage();
        }
        
        $data = [
            'name' => $name,
            'passengers' => $passengers
        ];
        if ($fileName != '')
        {
            $data['img'] = $fileName;
        }

        $cdate = time();
        if ($id == '')
        {
            $data['id'] = getNewId();
            $data['cdate'] = $cdate;
            Crud::insert(self::$table, $data);
            self::$data = ['msg' => 'Record added successfully'];
        }
        else
        {
            $data['mdate'] = $cdate;
            Crud::update(
                self::$table,
                $data,
                ['id' => $id]
            );
            self::$data = ['msg' => 'Record updated successfully'];
        }
    }

    public static function deleteVehicle()
    {
        $id = $_REQUEST['id'];

        //Crud::delete(self::$table, ['id' => $id]);
        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
        );
    }

    public static function getVehicles($arFields=['*'], $typeId='')
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

    public static function getVehiclesList()
    {
        $rs = self::getVehicles(['id, name, passengers, img, cdate, mdate']);
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
                    <img src="{$rootPath}/images/vehicle/{$r['img']}" class="adminTableImg">
EOQ;
                }

                $row = [
                    'sn' => $sn,
                    'name' => $r['name'],
                    'passengers' => $r['passengers'],
                    'img' => $imgPath,
                    'cdate' => getFormattedDate($r['cdate']),
                    'mdate' => getFormattedDate($r['mdate'])
                ];
                $row['edit'] = <<<EOQ
                <button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editVehicle('{$id}')">
                    Edit
                </button>
EOQ;
                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteVehicle('{$id}')">
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

    //CLIENT SIDE
    public static function getVehicle($id, $arFields=['*'])
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

    public static function getClientVehicles($page=1, $arFields=['*'])
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
        $data = [
            'columns' => $fields,
            'where' => [
                'deleted' => 0
            ],
            'return_type' => 'all',
            'order' => 'name DESC',
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

    public static function getVehiclesContent($page=1)
    {
        $rs = self::getClientVehicles($page);
        if (count($rs) == 0)
        {
            return 'No vehicle found';
        }
        $output = '';
        foreach($rs as $r)
        {
            $id = $r['id'];
            $img = !empty($r['img']) ? $r['img'] : 'boxed-bg.png';
            $imgPath = 'images/vehicle/'.$img;
            $name = stringToTitle($r['name']);
            $passengers = $r['passengers'];
            $output .= <<<EOQ
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                <div class="location-card style-2 tours-section">
                    <div class="image-wrapper">
                        <div class="image-inner tourImgWrapper">
                            <a href="javascript:;" onclick="showFullVehicleImg('{$id}')"><img src="{$imgPath}" alt="{$name}"></a>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="content-inner">
                            <span class="content-title"><a href="javascript:;" onclick="showFullVehicleImg('{$id}')" class="font-size-20">{$name}</a></span>
                            <span class="content-title"><a href="javascript:;" class="font-size-15">{$passengers} passengers</a></span>
                            <div class="btn-wrapper">
                                <a href="javascript:;" onclick="showFullVehicleImg('{$id}')" class="theme-btn theme-btn-padding-2">View Full Image <i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOQ;
        }

        return $output;
    }

    public static function getVehiclesTotal()
    {
        $rsTotal = Crud::select(
            self::$table,
            [
                'columns' => 'COUNT(id) AS total',
                'where' => [
                    'deleted' => 0
                ],
            ]
        );
        return doTypeCastDouble($rsTotal['total']);
    }

    public static function getVehiclesPagination($page=1)
    {
        $total = self::getVehiclesTotal();
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
        <div class="basic-pagination" id="appVehiclesPagination">
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

    public static function getAppVehiclesPaginationData()
    {
        $page = doTypeCastInt($_REQUEST['page']);
        $pagination = self::getVehiclesPagination($page);
        $list = self::getVehiclesContent($page);

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