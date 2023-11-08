<?php
namespace AbcTravels\Destination;

use AbcTravels\Crud\Crud;

class Destination
{
    public static $table = DEF_TBL_DESTINATIONS;
    public static $tableTours = DEF_TBL_TOURS;
    public static $data = [];
    static $appPerPage = 15;

    public static function getDestinationsAll($arFields=['*'], $typeId='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        $order = 'cdate DESC';
        if ($typeId == 'dropdown')
        {
            $order = 'name DESC';
        }
        $arFilter = [
            'columns' => $fields,
            'where' => ['deleted' => 0],
            'return_type' => 'all',
            'order' => $order
        ];
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    public static function getDestinationsDropdownOptions($destinationId='', $useShortName=false)
    {
        $options = '';
        $rs = self::getDestinationsAll(['id, name', 'short_name'], 'dropdown');
        if (count($rs) > 0)
        {
            foreach($rs as $r)
            {
                $name = stringToTitle($r['name']);
                $id = $r['id'];
                if ($useShortName)
                {
                    $id = $r['short_name'];
                }
                $selected = '';
                if ($destinationId != '')
                {
                    if ($destinationId == $id)
                    {
                        $selected = 'selected';
                    }
                }
                $options .= <<<EOQ
                <option value="{$id}" {$selected}>{$name}</option>
EOQ;
            }
        }
        return $options;
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

    public static function getDestinations($page=1, $arFields=['*'])
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

    public static function getDestinationsContent($page=1)
    {
        $rs = self::getDestinations($page);
        if (count($rs) == 0)
        {
            return 'No destination found';
        }
        $output = '';
        foreach($rs as $r)
        {
            $img = !empty($r['img']) ? $r['img'] : 'boxed-bg.png';
            $imgPath = 'images/destination/'.$img;
            $name = stringToTitle($r['name']);
            $shortName = $r['short_name'];
            $output .= <<<EOQ
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
                <div class="location-card style-2 tours-section">
                    <div class="image-wrapper">
                        <div class="image-inner tourImgWrapper">
                            <a href="tour?package={$shortName}"><img src="{$imgPath}" alt="{$name}"></a>
                        </div>
                    </div>
                    <div class="content-wrapper">
                        <div class="content-inner">
                            <span class="content-title"><a href="tour?package={$shortName}" class="font-size-20">{$name}</a></span>
                            <div class="btn-wrapper">
                                <a href="tour?package={$shortName}" class="theme-btn theme-btn-padding-2">View Package <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
EOQ;
        }

        return $output;
    }

    public static function getDestinationsTotal()
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

    public static function getDestinationsPagination($page=1)
    {
        $total = self::getDestinationsTotal();
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
        <div class="basic-pagination" id="appDestinationsPagination">
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

    public static function getAppDestinationsPaginationData()
    {
        $page = doTypeCastInt($_REQUEST['page']);
        $pagination = self::getDestinationsPagination($page);
        $list = self::getDestinationsContent($page);

        $data = [
            'pagination' => $pagination,
            'list' => $list
        ];

        self::$data = [
            'status' => true,
            'data' => $data
        ];
    }

    public static function getDestinationInfoByShortName($shortName, $arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;

        return Crud::select(
            self::$table,
            [
                'columns' => $fields,
                'where' => [
                    'short_name' => $shortName
                    , 'deleted' => 0
                ]
            ]
        );
    }
}