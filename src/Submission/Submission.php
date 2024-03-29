<?php
namespace AbcTravels\Submission;

use AbcTravels\Crud\Crud;
use AbcTravels\Destination\Destination;
use AbcTravels\SendMail\SendMail;
use Exception;

class Submission
{
    public static $table = DEF_TBL_SUBMISSIONS;
    static $data = [];
    static $lineBreak = "<br>";//"\r\n";

    public static function addEnquiry()
    {
        global $arSiteSettings;

        $name = trim($_REQUEST['name']);
        $email = trim($_REQUEST['email']);
        $mobile = trim($_REQUEST['mobile']);
        $nationality = trim($_REQUEST['nationality']);
        $destinationId = trim($_REQUEST['destination']);
        $arrivalDate = trim($_REQUEST['arrivalDate']);
        $departureDate = trim($_REQUEST['departureDate']);
        $numAdult = doTypeCastInt($_REQUEST['numAdult']);
        $numChildren = doTypeCastInt($_REQUEST['numChildren']);
        $childrenAges = trim($_REQUEST['childrenAges']);
        $message = isset($_REQUEST['message']) ? trim($_REQUEST['message']) : '';
        $tourId = isset($_REQUEST['tourId']) ? trim($_REQUEST['tourId']) : '';
        $tourDestination = doTypeCastInt($_REQUEST['tourDestination']) ? trim($_REQUEST['tourDestination']) : 0;
        $action = trim($_REQUEST['action']);
        //$recaptchaResponse = trim($_REQUEST['g-recaptcha-response']); //only for verification

        if (strlen($arrivalDate) == 10 && $arrivalDate <= date('Y-m-d'))
        {
            throw new Exception('Arrival date cannot be before or same as the current date!');
        }
        else if (strlen($departureDate) == 10 && $departureDate <= date('Y-m-d'))
        {
            throw new Exception('Departure date cannot be before or same as the current date!');
        }
        else if (strlen($arrivalDate) == 10 && strlen($departureDate) == 10)
        {
            if ($arrivalDate > $departureDate)
            {
                throw new Exception('Arrival date cannot be after departure date!');
            }
        }
        else if (strlen($arrivalDate) == 10 && strlen($departureDate) == 10)
        {
            if ($arrivalDate == $departureDate)
            {
                throw new Exception('Arrival date cannot be same as the departure date!');
            }
        }

        $arDetails = [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'nationality' => $nationality,
            'destination' => $destinationId,
            'arrivalDate' => $arrivalDate,
            'departureDate' => $departureDate,
            'numAdult' => $numAdult,
            'numChildren' => $numChildren,
            'childrenAges' => $childrenAges,
            'tourId' => $tourId,
            'tourDestination' => $tourDestination,
            'message' => $message
        ];

        switch($action)
        {
            case 'addTourEnquiry':
                $typeId = DEF_SUBMISSION_TYPE_TOUR_ENQUIRY;
            break;
            case 'addHotelEnquiry':
                $typeId = DEF_SUBMISSION_TYPE_HOTEL_ENQUIRY;
            break;
            case 'addVehicleEnquiry':
                $typeId = DEF_SUBMISSION_TYPE_VEHICLE_ENQUIRY;
            break;
            default:
                $typeId = DEF_SUBMISSION_TYPE_COMMON_ENQUIRY;
            break;
        }
        $data = [
            'type_id' => $typeId,
            'details' => json_encode($arDetails)
        ];

        //check duplicate submission
        self::checkDuplicate($data);

        //send email
        $rsx = Destination::getDestination($destinationId, ['name']);
        $destination = $rsx['name'];
        $type = getSubmissionType($typeId);
        $submissionTypeLink = '';
        if ($tourId != '')
        {
            if (strlen($tourId) == 36)
            {
                switch($tourDestination)
                {
                    case 1:
                        $table = DEF_TBL_DESTINATIONS;
                        $fieldName = 'name';
                        $path = 'tour';
                    break;
                    case 2:
                        $table = DEF_TBL_VEHICLES;
                        $fieldName = 'name';
                        $path = 'vehicles';
                    break;
                    default:
                        $table = DEF_TBL_TOURS;
                        $fieldName = 'title';
                        $path = 'tour-details';
                    break;
                }

                $columns = $fieldName;
                if ($tourDestination != 2)
                {
                    $columns .= ", short_name";
                }
                $rsx = Crud::select(
                    $table,
                    [
                        'columns' => "{$columns}",
                        'where' => [
                            'id' => $tourId
                        ]
                    ]
                );
                if ($rsx)
                {
                    $tourName = $rsx[$fieldName];
                    
                    $siteRootPath = DEF_FULL_ROOT_PATH;
                    $type = $tourName;

                    $submissionTypeHref = "$siteRootPath/$path";

                    if ($tourDestination != 2)
                    {
                        $shortName = $rsx['short_name'];
                        $submissionTypeHref .= "?package=$shortName";
                    }
                    
                    $submissionTypeLink = <<<EOQ
                    <a href='{$submissionTypeHref}'>{$tourName}</a>
EOQ;
                }
            }
        }

        $startingMsg = self::getStartingMessage();
        //$body = $startingMsg;
        $lineBreak = self::$lineBreak;
        $body = "Submission Type: $type" . $lineBreak;
        if ($submissionTypeLink != '')
        {
            $lblPackageLink = 'Package Link';
            if ($tourDestination == 2)
            {
                $lblPackageLink = 'Link';
            }
            $body .= $lblPackageLink.":" . $lineBreak;
            $body .= $submissionTypeLink . $lineBreak;
        }
        $body .= "Name: $name" . $lineBreak;
        $body .= "Email: $email" . $lineBreak;
        $body .= "Mobile: $mobile" . $lineBreak;
        $body .= "Nationality: $nationality" . $lineBreak;
        $body .= "Destination: $destination" . $lineBreak;
        $body .= "Arrival Date: $arrivalDate" . $lineBreak;
        $body .= "Departure Date: $departureDate" . $lineBreak;
        $body .= "Number of Adults: $numAdult" . $lineBreak;
        $body .= "Number of Children: $numChildren" . $lineBreak;
        $body .= "Ages of Children: $childrenAges" . $lineBreak;
        $body .= "Message: $message" . $lineBreak;
        
        $arParams = [
            'mailTo' => $arSiteSettings['email'],
            'toName' => $arSiteSettings['name'],//'Booking Admin',
            'mailFrom' => $arSiteSettings['email'],
            'fromName' => $arSiteSettings['name'],
            //'arCC' => [$arSiteSettings['booking_email']],
            'subject' => self::getEmailSubject($type),
            'body' => $startingMsg.$body
        ];
        //SendMail::sendMail($arParams);
        //SendMail::sendDefaultMail($arParams);
        SendMail::sendCustomMail($arParams);

        //Send confirmation email to Customer
        $startingMsg = self::getStartingMessage('customers', $name);
        $arParams['mailTo'] = $email;
        $arParams['toName'] = $name;
        $arParams['subject'] = self::getEmailSubject($type, 'customers');
        $arParams['body'] = $startingMsg.$body;
        $arParams['addCC'] = false;
        SendMail::sendCustomMail($arParams);

        $data['id'] = getNewId();
        $data['cdate'] = time();
        Crud::insert(
            self::$table,
            $data
        );
    }

    public static function addContact()
    {
        global $arSiteSettings;

        $name = trim($_REQUEST['name']);
        $email = trim($_REQUEST['email']);
        $mobile = trim($_REQUEST['mobile']);
        $subject = trim($_REQUEST['subject']);
        $message = trim($_REQUEST['message']);

        $arDetails = [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'subject' => $subject,
            'message' => $message
        ];

        $data = [
            'type_id' => DEF_SUBMISSION_TYPE_CONTACT,
            'details' => json_encode($arDetails)
        ];

        //check duplicate submission
        self::checkDuplicate($data);

        //send email
        $type = getSubmissionType(DEF_SUBMISSION_TYPE_CONTACT);

        $startingMsg = self::getStartingMessage();
        $body = "Submission Type: $type" . "\r\n";
        $body .= "Name: $name" . "\r\n";
        $body .= "Email: $email" . "\r\n";
        $body .= "Mobile: $mobile" . "\r\n";
        $body .= "Subject: $subject" . "\r\n";
        $body .= "Message: $message" . "\r\n";

        $arParams = [
            'mailTo' => $arSiteSettings['email'],
            'toName' => $arSiteSettings['name'],
            'mailFrom' => $arSiteSettings['email'],
            'fromName' => $arSiteSettings['name'],
            'subject' => self::getEmailSubject($subject),
            'body' => $startingMsg.$body
        ];
        SendMail::sendCustomMail($arParams);

        //Send confirmation email to Customer
        $startingMsg = self::getStartingMessage('customers', $name);
        $arParams['mailTo'] = $email;
        $arParams['toName'] = $name;
        $arParams['subject'] = self::getEmailSubject($type, 'customers');
        $arParams['body'] = $startingMsg.$body;
        $arParams['addCC'] = false;
        SendMail::sendCustomMail($arParams);

        $data['id'] = getNewId();
        $data['cdate'] = time();
        Crud::insert(
            self::$table,
            $data
        );
    }

    public static function customizeTrip()
    {
        global $arSiteSettings;

        $name = trim($_REQUEST['name']);
        $email = trim($_REQUEST['email']);
        $mobile = trim($_REQUEST['mobile']);
        $nationality = trim($_REQUEST['nationality']);
        $destinationId = trim($_REQUEST['destination']);
        $tourDuration = trim($_REQUEST['tourDuration']);
        $travellingDate = trim($_REQUEST['travellingDate']);
        $message = isset($_REQUEST['message']) ? trim($_REQUEST['message']) : '';

        if ($travellingDate <= date('Y-m-d'))
        {
            throw new Exception('Travelling date cannot be before or same as the current date!');
        }

        $arDetails = [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'nationality' => $nationality,
            'destination' => $destinationId,
            'tourDuration' => $tourDuration,
            'travellingDate' => $travellingDate,
            'message' => $message
        ];

        $data = [
            'type_id' => DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR,
            'details' => json_encode($arDetails)
        ];

        //check duplicate submission
        self::checkDuplicate($data);

        //send email
        $rsx = Destination::getDestination($destinationId, ['name']);
        $destination = $rsx['name'];
        $type = getSubmissionType(DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR);

        $startingMsg = self::getStartingMessage();
        $body = "Submission Type: $type" . "\r\n";
        $body .= "Name: $name" . "\r\n";
        $body .= "Email: $email" . "\r\n";
        $body .= "Mobile: $mobile" . "\r\n";
        $body .= "Nationality: $nationality" . "\r\n";
        $body .= "Destination: $destination" . "\r\n";
        $body .= "Tour Duration: $tourDuration" . "\r\n";
        $body .= "Travelling Date: $travellingDate" . "\r\n";
        $body .= "Message: $message" . "\r\n";

        $arParams = [
            'mailTo' => $arSiteSettings['email'],
            'toName' => $arSiteSettings['name'],
            'mailFrom' => $arSiteSettings['email'],
            'fromName' => $arSiteSettings['name'],
            'subject' => self::getEmailSubject($type),
            'body' => $startingMsg.$body
        ];
        SendMail::sendCustomMail($arParams);

        //Send confirmation email to Customer
        $startingMsg = self::getStartingMessage('customers', $name);
        $arParams['mailTo'] = $email;
        $arParams['toName'] = $name;
        $arParams['subject'] = self::getEmailSubject($type, 'customers');
        $arParams['body'] = $startingMsg.$body;
        $arParams['addCC'] = false;
        SendMail::sendCustomMail($arParams);

        $data['id'] = getNewId();
        $data['cdate'] = time();
        Crud::insert(
            self::$table,
            $data
        );

        $data['id'] = getNewId();
        $data['cdate'] = time();
        Crud::insert(
            self::$table,
            $data
        );
    }

    protected static function checkDuplicate($data)
    {
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'cdate',
                'where' => $data,
                'order' => 'cdate DESC',
                'limit' => 1
            ]
        );
        if (count($rs) > 0)
        {
            $currentTime = time();
            $submissionTime = $rs['cdate'];
            $reversedTime = strtotime("+2 minutes", $submissionTime);
            if($currentTime < $reversedTime)
            {
                //submission was done in less than 2 minutes ago
                throw new Exception('You already made this submission. Thank you!');
            }
        }
    }

    public static function getSubmission($id, $arFields=['*'])
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

    public static function getSubmissions($arFields=['*'])
    {
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

        $arFilter = [
            'columns' => $fields,
            'where' => ['deleted' => 0],
            'return_type' => 'all',
            'order' => 'cdate DESC'
        ];
        if ($action == 'getdashboardsubmissions')
        {
            $arFilter['limit'] = '0, 10';
        }
        
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    public static function getSubmissionsList()
    {
        $rs = self::getSubmissions(['id', 'type_id', 'cdate']);
        if (count($rs) > 0)
        {
            $rows = [];
            $sn = 1;
            foreach($rs as $r)
            {
                $id = $r['id'];

                $row = [
                    'sn' => $sn,
                    'type' => getSubmissionType($r['type_id']),
                    'cdate' => getFormattedDate($r['cdate'])
                ];
                $row['details'] = <<<EOQ
                <a href="app/submission?id={$id}" class="btn btn-primary btn-rounded btn-icon">View Details</a>
EOQ;
                /*$row['delete'] = <<<EOQ
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="deleteSubmission('{$id}')">
                    Delete
                </button>
EOQ;*/
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

    public static function deleteSubmission()
    {
        $id = trim($_REQUEST['id']);

        Crud::update(
            self::$table,
            ['deleted' => 1],
            ['id' => $id]
        );
    }

    public static function getSubmissionDetailsHtml($typeId, $arDetails)
    {
        $output = '';
        if (in_array($typeId,
            [
                DEF_SUBMISSION_TYPE_COMMON_ENQUIRY
                , DEF_SUBMISSION_TYPE_TOUR_ENQUIRY
                , DEF_SUBMISSION_TYPE_HOTEL_ENQUIRY
                , DEF_SUBMISSION_TYPE_VEHICLE_ENQUIRY
                , DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR
                , DEF_SUBMISSION_TYPE_CONTACT
            ]
        ))
        {
            $tourId = array_key_exists('tourId', $arDetails) ? $arDetails['tourId'] : '';
            if ($tourId != '')
            {
                $tourDestination = array_key_exists('tourDestination', $arDetails) ? doTypeCastInt($arDetails['tourDestination']) : 0;
                if (strlen($tourId) == 36 
                    && array_key_exists('tourDestination', $arDetails)
                )
                {
                    $tourDestination = doTypeCastInt($arDetails['tourDestination']);
                    switch($tourDestination)
                    {
                        case 1:
                            $table = DEF_TBL_DESTINATIONS;
                            $fieldName = 'name';
                            $path = 'tour';
                        break;
                        case 2:
                            $table = DEF_TBL_VEHICLES;
                            $fieldName = 'name';
                            $path = 'vehicles';
                        break;
                        default:
                            $table = DEF_TBL_TOURS;
                            $fieldName = 'title';
                            $path = 'tour-details';
                        break;
                    }

                    $columns = $fieldName;
                    if ($tourDestination != 2)
                    {
                        $columns .= ", short_name";
                    }
                    
                    $rsx = Crud::select(
                        $table,
                        [
                            'columns' => "{$columns}",
                            'where' => [
                                'id' => $tourId
                            ]
                        ]
                    );
                    if ($rsx)
                    {
                        $name = $rsx[$fieldName];

                        $siteRootPath = DEF_FULL_ROOT_PATH;
                        $packageHref = "$siteRootPath/$path";
                        $lblPackageLink = 'Link';
                        if ($tourDestination != 2)
                        {
                            $shortName = $rsx['short_name'];
                            $packageHref .= "?package=$shortName";
                            $lblPackageLink = 'Package Link';
                        }
                        $output .= <<<EOQ
                        <p><strong>{$lblPackageLink}:</strong> <a href='{$packageHref}'>{$name}</a></p>
EOQ;
                    }
                }
            }
            $output .= <<<EOQ
            <p><strong>Name:</strong> {$arDetails['name']}</p>
            <p><strong>Email:</strong> {$arDetails['email']}</p>
            <p><strong>Mobile:</strong> {$arDetails['mobile']}</p>
EOQ;
            if (in_array($typeId, [
                DEF_SUBMISSION_TYPE_COMMON_ENQUIRY
                , DEF_SUBMISSION_TYPE_TOUR_ENQUIRY
                , DEF_SUBMISSION_TYPE_HOTEL_ENQUIRY
                , DEF_SUBMISSION_TYPE_VEHICLE_ENQUIRY
                , DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR
            ]))
            {
                $rs = Destination::getDestination($arDetails['destination'], ['name']);
                $destination = $rs['name'];
                $output .= <<<EOQ
                <p><strong>Nationality:</strong> {$arDetails['nationality']}</p>
                <p><strong>Destination:</strong> {$destination}</p>
EOQ;
                if ($typeId != DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR)
                {
                    $numOfAdult = doTypeCastInt($arDetails['numAdult']);
                    $numOfChildren = doTypeCastInt($arDetails['numChildren']);
                    $output .= <<<EOQ
                    <p><strong>Arrival Date:</strong> {$arDetails['arrivalDate']}</p>
                    <p><strong>Departure Date:</strong> {$arDetails['departureDate']}</p>
                    <p><strong>Number of Adults:</strong> {$numOfAdult}</p>
                    <p><strong>Number of Children:</strong> {$numOfChildren}</p>
                    <p><strong>Ages of Children:</strong> {$arDetails['childrenAges']}</p>
EOQ;
                }
                else
                {
                    $tourDuration = doTypeCastInt($arDetails['tourDuration']);
                    $output .= <<<EOQ
                    <p><strong>Tour Duration:</strong> {$tourDuration} Days</p>
                    <p><strong>Travelling Date:</strong> {$arDetails['travellingDate']}</p>
EOQ;
                }

                if (in_array($typeId, [
                        DEF_SUBMISSION_TYPE_COMMON_ENQUIRY
                        , DEF_SUBMISSION_TYPE_TOUR_ENQUIRY
                        , DEF_SUBMISSION_TYPE_HOTEL_ENQUIRY
                        , DEF_SUBMISSION_TYPE_VEHICLE_ENQUIRY
                        , DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR
                    ]
                ))
                {
                    $output .= <<<EOQ
                    <p><strong>Message:</strong> {$arDetails['message']}</p>
EOQ;
                }
            }
            if ($typeId == DEF_SUBMISSION_TYPE_CONTACT)
            {
                $output .= <<<EOQ
                <p><strong>Subject:</strong> {$arDetails['subject']}</p>
                <p><strong>Message:</strong> {$arDetails['message']}</p>
EOQ;
            }
        }

        return $output;
    }

    protected static function getStartingMessage($typeId='', $customerName='')
    {
        global $arSiteSettings;

        $siteName = $arSiteSettings['name'];
        $lineBreak = self::$lineBreak;

        $msg = '';
        if ($typeId == 'customers')
        {
            $msg = "Dear $customerName," . $lineBreak.$lineBreak;
            $msg .= "This is to notify you that we have received your submission below as sent on $siteName. $lineBreak";
            $msg .= "We will get back to you shortly." . $lineBreak.$lineBreak;
        }
        else
        {
            $msg = "Dear Team," . $lineBreak.$lineBreak;
            $msg .= "Please see below submission from $siteName." . $lineBreak.$lineBreak;
        }
        
        return $msg;
    }

    protected static function getEmailSubject($text, $typeId='')
    {
        global $arSiteSettings;

        $subjectPrefix = "Mail From ";
        if ($typeId == 'customers')
        {
            $subjectPrefix = "Confirmation From ";
        }
        $subject = $subjectPrefix.$arSiteSettings['name'];
        if ($text != '')
        {
            $subject .= " - $text";
        }
        return $subject;
    }
}