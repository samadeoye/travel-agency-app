<?php
namespace AbcTravels\Hotel;

use AbcTravels\Crud\Crud;
use AbcTravels\Admin\Image\Image;
use Exception;

class Hotel
{
    protected static $table = DEF_TBL_HOTEL_ROOMS;
    static $data = [];
    static $appPerPage = 15;

    public static function addOrUpdateHotelRoom()
    {
        $name = stringToUpper(trim($_REQUEST['name']));
        $details = trim($_REQUEST['details']);
        //$link = trim($_REQUEST['link']);
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        if (Crud::checkDuplicate(self::$table, 'name', $name, $id, true))
        {
            throw new Exception('Record with this name already exists.');
        }

        $fileName = '';
        $imgFileSize = $_FILES['featuredImg']['size'];
        if ($imgFileSize > 0)
        {
            //upload image
            Image::$directory = 'images/hotelroom/';
            $fileName = Image::uploadImage();
        }
        
        $data = [
            'name' => $name,
            //'link' => $link,
            'details' => $details
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

    public static function getHotelRooms($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        
        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => ['deleted' => 0],
                'return_type' => 'all',
                'order' => 'cdate DESC'
            ]
        );
    }

    public static function getHotelRoomsList()
    {
        $rs = self::getHotelRooms(['id, name, img, details, cdate, mdate']);
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
                    <img src="{$rootPath}/images/hotelroom/{$r['img']}" class="adminTableImg">
EOQ;
                }

                $row = [
                    'sn' => $sn,
                    'name' => $r['name'],
                    'details' => $r['details'],
                    'img' => $imgPath,
                    //'link' => $r['link'],
                    'cdate' => getFormattedDate($r['cdate']),
                    'mdate' => getFormattedDate($r['mdate'])
                ];
                $row['edit'] = <<<EOQ
                <button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editHotelRoom('{$id}')">
                    Edit
                </button>
EOQ;
                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteHotelRoom('{$id}')">
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

    public static function getHotel($id, $arFields=['*'])
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

    public static function deleteHotelRoom()
    {
        $id = $_REQUEST['id'];

        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
        );
    }

    //CLIENT SIDE
    public static function getClientRooms($page=1, $arFields=['*'])
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

    public static function getRoomsContent($page=1)
    {
        $rs = self::getClientRooms($page);
        if (count($rs) == 0)
        {
            return 'No room found';
        }
        $output = '';
        foreach($rs as $r)
        {
            $id = $r['id'];
            $img = !empty($r['img']) ? $r['img'] : 'boxed-bg.png';
            $imgPath = 'images/hotelroom/'.$img;
            $name = stringToTitle($r['name']);
            //$link = $r['link'];
            $details = $r['details'];
            $output .= <<<EOQ
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                <div class="location-card style-2 tours-section">
                    <div class="image-wrapper">
                        <div class="image-inner tourImgWrapper">
                            <a href="javascript:;" onclick="openEnquireNowModal()"><img src="{$imgPath}" alt="{$name}"></a>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="content-inner">
                            <span class="content-title"><a href="javascript:;" onclick="openEnquireNowModal()" class="font-size-20">{$name}</a></span>
                            <span>{$details}</span>
                            <div class="btn-wrapper">
                                <a href="javascript:;" onclick="openEnquireNowModal()" class="theme-btn theme-btn-padding-2">Book now <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOQ;
        }

        return $output;
    }

    public static function getRoomsTotal()
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

    public static function getRoomsPagination($page=1)
    {
        $total = self::getRoomsTotal();
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
        <div class="basic-pagination" id="appRoomsPagination">
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

    public static function getAppRoomsPaginationData()
    {
        $page = doTypeCastInt($_REQUEST['page']);
        $pagination = self::getRoomsPagination($page);
        $list = self::getRoomsContent($page);

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