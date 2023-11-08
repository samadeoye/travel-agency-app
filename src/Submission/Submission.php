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
            'childrenAges' => $childrenAges
        ];

        $typeId = DEF_SUBMISSION_TYPE_COMMON_ENQUIRY;
        if ($action == 'addTourEnquiry')
        {
            $typeId = DEF_SUBMISSION_TYPE_TOUR_ENQUIRY;
            $arDetails['message'] = $message;
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

        $bodyHtml = <<<EOQ
        <div style="padding:20px 15px;">
            <b>Submission Type: {$type}</b><br><br>
            <b>Name:</b> {$name}<br>
            <b>Email:</b> {$email}<br>
            <b>Mobile:</b> {$mobile}<br>
            <b>Nationality:</b> {$nationality}<br>
            <b>Destination:</b> {$destination}<br>
            <b>Arrival Date:</b> {$arrivalDate}<br>
            <b>Departure Date:</b> {$departureDate}<br>
            <b>Number of Adults:</b> {$numAdult}<br>
            <b>Number of Children:</b> {$numChildren}<br>
            <b>Ages of Children:</b> {$childrenAges}<br>
EOQ;
        if ($action == 'addTourEnquiry')
        {
            $bodyHtml .= <<<EOQ
            <b>Message:</b> {$message}
EOQ;
        }
        $bodyHtml . <<<EOQ
        </div>
EOQ;
        
        $arParams = [
            'mailTo' => $arSiteSettings['booking_email'],
            'toName' => 'Admin',
            'mailFrom' => $email,
            'fromName' => $name,
            'arCC' => [$arSiteSettings['email']],
            'subject' => getSubmissionType($typeId),
            'isHtml' => true,
            'bodyHtml' => $bodyHtml
        ];
        SendMail::sendMail($arParams);

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

        $bodyHtml = <<<EOQ
        <div style="padding:20px 15px;">
            <b>Submission Type:</b> {$type}<br><br>
            <b>Name:</b> {$name}<br>
            <b>Email:</b> {$email}<br>
            <b>Mobile:</b> {$mobile}<br>
            <b>Subject:</b> {$subject}<br>
            <b>Message:</b> {$message}
        </div>
EOQ;
        $arParams = [
            'mailTo' => $arSiteSettings['booking_email'],
            'toName' => $arSiteSettings['name'],
            'mailFrom' => $email,
            'fromName' => $name,
            'arCC' => [$arSiteSettings['email']],
            'isHtml' => true,
            'bodyHtml' => $bodyHtml
        ];
        if ($subject != '')
        {
            $arParams['subject'] = $subject;
        }
        SendMail::sendMail($arParams);

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

        $bodyHtml = <<<EOQ
        <div style="padding:20px 15px;">
            <b>Submission Type:</b> {$type}<br><br>
            <b>Name:</b> {$name}<br>
            <b>Email:</b> {$email}<br>
            <b>Mobile:</b> {$mobile}<br>
            <b>Nationality:</b> {$nationality}<br>
            <b>Destination:</b> {$destination}<br>
            <b>Tour Duration:</b> {$tourDuration}<br>
            <b>Travelling Date:</b> {$travellingDate}<br>
            <b>Message:</b> {$message}
        </div>
EOQ;

        $arParams = [
            'mailTo' => $arSiteSettings['booking_email'],
            'toName' => 'Admin',
            'mailFrom' => $email,
            'fromName' => $name,
            'arCC' => [$arSiteSettings['email']],
            'subject' => $type,
            'isHtml' => true,
            'bodyHtml' => $bodyHtml
        ];
        SendMail::sendMail($arParams);

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
        $fields = is_array($arFields) ? implode(',', $arFields) : $arFields;
        return Crud::select(
            self::$table,
            $arFilter
        );
    }

    public static function getSubmissionsList()
    {
        $rs = self::getSubmissions(['id', 'type_id', 'cdate']);
        if(count($rs) > 0)
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
                , DEF_SUBMISSION_TYPE_CUSTOMIZED_TOUR
                , DEF_SUBMISSION_TYPE_CONTACT
            ]
        ))
        {
            $output .= <<<EOQ
            <p><strong>Name:</strong> {$arDetails['name']}</p>
            <p><strong>Email:</strong> {$arDetails['email']}</p>
            <p><strong>Mobile:</strong> {$arDetails['mobile']}</p>
EOQ;
            if (in_array($typeId, [
                DEF_SUBMISSION_TYPE_COMMON_ENQUIRY
                , DEF_SUBMISSION_TYPE_TOUR_ENQUIRY
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
                        DEF_SUBMISSION_TYPE_TOUR_ENQUIRY
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
}