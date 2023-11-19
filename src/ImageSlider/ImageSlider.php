<?php
namespace AbcTravels\ImageSlider;

use Exception;
use AbcTravels\Crud\Crud;
use AbcTravels\Admin\Image\Image;

class ImageSlider
{
    public static $table = DEF_TBL_IMAGE_SLIDERS;
    public static $data = [];
    static $appPerPage = 15;

    public static function addOrUpdateSlider()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

        $fileName = '';
        $fieldId = 'sliderImage';
        $imgFileSize = $_FILES[$fieldId]['size'];
        if ($imgFileSize > 0)
        {
            //Get the count of the current images - should not exceed 3
            $rs =  Crud::select(
                self::$table,
                [
                    'columns' => 'COUNT(id) AS count',
                    'where' => ['deleted' => 0]
                ]
            );
            if ($rs)
            {
                if ($rs['count'] >= 3)
                {
                    throw new Exception('You cannot add more than three images in the homepage slider!');
                }
            }

            //upload image
            Image::$directory = 'images/hero-section/';
            Image::$fieldId = $fieldId;
            $fileName = Image::uploadImage();
            if ($fileName == '')
            {
                throw new Exception('An error occured. Please try again.');
            }
        }
        else
        {
            throw new Exception('Please select an image!');
        }

        $cdate = time();
        $data = [
            'img' => $fileName
        ];
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

    public static function deleteSlider()
    {
        $id = $_REQUEST['id'];

        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
        );
    }

    public static function getSliders($arFields=['*'], $typeId='')
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        $order = 'cdate DESC';
        if ($typeId == 'client')
        {
            $order = 'cdate ASC';
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

    public static function getSlidersList()
    {
        $rs = self::getSliders(['id, img, cdate, mdate']);
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
                    <img src="{$rootPath}/images/hero-section/{$r['img']}" class="adminTableImg">
EOQ;
                }

                $row = [
                    'sn' => $sn,
                    'img' => $imgPath,
                    'cdate' => getFormattedDate($r['cdate']),
                    'mdate' => getFormattedDate($r['mdate'])
                ];
                $row['edit'] = <<<EOQ
                <button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="editSlider('{$id}')">
                    Edit
                </button>
EOQ;
                $row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteSlider('{$id}')">
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
    public static function getSlider($id, $arFields=['*'])
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
    
    public static function getSlidersContent()
    {
        $rs = self::getSliders(['img'], 'client');
        $output = '';
        if (count($rs) > 0)
        {
            $output .= <<<EOQ
            <div class="slider-area style-2">
                <div class="slider-arrow-btn-wrapper">
                    <button type="button" class="header-slider-arrow-btn prev-btn" id="trigger_header_slider_prev"><i class="fa-solid fa-arrow-left"></i></button>
                    <button type="button" class="header-slider-arrow-btn next-btn" id="trigger_header_slider_next"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
                <div class="slider-wrapper" id="slider-wrapper">
EOQ;
            foreach($rs as $r)
            {
                $imgPath = 'images/hero-section/'.$r['img'];
                $output .= <<<EOQ
                <div class="single-slider-wrapper">
                    <div class="single-slider" style="background-image: url('{$imgPath}');">
                        <div class="container h-100 align-self-center">
                            <div class="row h-100">
                                <div class="col-md-6 align-self-center order-2 order-md-1">
                                    <div class="slider-content-wrapper">
                                        <div class="slider-content p-3">
                                            <span class="slider-short-title text-on-header">Tour and Travels</span>
                                            <h1 class="slider-title text-on-header">Experience the ultimate luxury</h1>
                                            <div class="slider-btn-wrapper"><a href="tours" class="theme-btn style-2">Get started</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
EOQ;
            }
            $output .= <<<EOQ
            </div>
            </div>
EOQ;
        }

        return $output;
    }
}