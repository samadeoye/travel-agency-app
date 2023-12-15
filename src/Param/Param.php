<?php
namespace AbcTravels\Param;

class Param
{
    public static function getRequestParams($action)
    {
        $data = [];
        switch($action)
        {
            case 'register':
                $data = [
                    'fname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'First Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'lname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Last Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ],
                    'password1' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password',
                        'required' => true
                    ],
                    'password2' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Confirm Password',
                        'required' => true
                    ],
                ];
            break;

            case 'login':
                $data = [
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ],
                    'password' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password',
                        'required' => true
                    ]
                ];
            break;

            case 'changepassword':
                $data = [
                    'currentPassword' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Current Password',
                        'required' => true
                    ],
                    'newPassword' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'New Password',
                        'required' => true
                    ],
                    'confirmPassword' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Confirm Password',
                        'required' => true
                    ]
                ];
            break;

            case 'updateprofile':
                $data = [
                    'fname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'First Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'lname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Last Name',
                        'required' => true,
                        'type' => 'string'
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,200],
                        'label' => 'Email',
                        'required' => true,
                        'type' => 'string',
                        'is_email' => true
                    ]
                ];
            break;

            case 'adddestination':
                $data = [
                    'name' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Destination Name',
                        'required' => true
                    ]
                ];
            break;

            case 'updatedestination':
                $data = [
                    'id' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Destination',
                        'required' => true
                    ],
                    'name' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Destination Name',
                        'required' => true
                    ]
                ];
            break;

            case 'sendContactForm':
                $data = [
                    'fname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'First Name',
                        'required' => true
                    ],
                    'lname' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Last Name',
                        'required' => true
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,100],
                        'label' => 'Email',
                        'required' => true
                    ],
                    'subject' => [
                        'method' => 'post',
                        'length' => [5,200],
                        'label' => 'Subject'
                    ],
                    'msg' => [
                        'method' => 'post',
                        'length' => [20,0],
                        'label' => 'Message',
                        'required' => true
                    ]
                ];
            break;

            case 'forgotPassVerifyEmail':
                $data = [
                    'email' => [
                        'method' => 'post',
                        'length' => [13,100],
                        'label' => 'Email',
                        'required' => true
                    ]
                ];
            break;

            case 'resetpassword':
                $data = [
                    'token' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Token',
                        'required' => true
                    ],
                    'password' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password',
                        'required' => true
                    ],
                    'password_confirm' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password Confirm',
                        'required' => true
                    ]
                ];
            break;

            case 'addCommonEnquiry':
            case 'addTourEnquiry':
            case 'addHotelEnquiry':
                $data = [
                    'name' => [
                        'method' => 'post',
                        'length' => [3,200],
                        'label' => 'Name',
                        'required' => true
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,150],
                        'label' => 'Email',
                        'required' => true
                    ],
                    'mobile' => [
                        'method' => 'post',
                        'length' => [6,16],
                        'label' => 'Mobile Number',
                        'required' => true
                    ],
                    'nationality' => [
                        'method' => 'post',
                        'length' => [3,200],
                        'label' => 'Nationality',
                        'required' => true
                    ],
                    'destination' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Destination',
                        'required' => true
                    ],
                    'arrivalDate' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Arrival Date',
                        'required' => false
                    ],
                    'departureDate' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Departure Date',
                        'required' => false
                    ],
                    'numAdult' => [
                        'method' => 'post',
                        'length' => [1,4],
                        'label' => 'Number of Adults',
                        'required' => true
                    ],
                    'numChildren' => [
                        'method' => 'post',
                        'length' => [1,4],
                        'label' => 'Number of Children',
                        'required' => false
                    ],
                    'childrenAges' => [
                        'method' => 'post',
                        'length' => [0,200],
                        'label' => 'Ages of Children',
                        'required' => false
                    ],
                    'g-recaptcha-response' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Recaptcha',
                        'required' => true
                    ],
                    'message' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Message',
                        'required' => false
                    ]
                ];
            break;

            case 'addContact':
                $data = [
                    'name' => [
                        'method' => 'post',
                        'length' => [3,200],
                        'label' => 'Name',
                        'required' => true
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,150],
                        'label' => 'Email',
                        'required' => true
                    ],
                    'mobile' => [
                        'method' => 'post',
                        'length' => [6,16],
                        'label' => 'Mobile Number',
                        'required' => true
                    ],
                    'subject' => [
                        'method' => 'post',
                        'length' => [3,200],
                        'label' => 'Subject',
                        'required' => false
                    ],
                    'message' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Message',
                        'required' => true
                    ],
                    'g-recaptcha-response' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Recaptcha',
                        'required' => true
                    ]
                ];
            break;

            case 'customizeTrip':
                $data = [
                    'name' => [
                        'method' => 'post',
                        'length' => [3,200],
                        'label' => 'Name',
                        'required' => true
                    ],
                    'email' => [
                        'method' => 'post',
                        'length' => [13,150],
                        'label' => 'Email',
                        'required' => true
                    ],
                    'mobile' => [
                        'method' => 'post',
                        'length' => [6,16],
                        'label' => 'Mobile Number',
                        'required' => true
                    ],
                    'nationality' => [
                        'method' => 'post',
                        'length' => [3,200],
                        'label' => 'Nationality',
                        'required' => true
                    ],
                    'destination' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Destination',
                        'required' => true
                    ],
                    'travellingDate' => [
                        'method' => 'post',
                        'length' => [10,10],
                        'label' => 'Travelling Date',
                        'required' => false
                    ],
                    'tourDuration' => [
                        'method' => 'post',
                        'length' => [1,4],
                        'label' => 'Tour Duration',
                        'required' => false
                    ],
                    'message' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Message',
                        'required' => false
                    ],
                    'g-recaptcha-response' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Recaptcha',
                        'required' => true
                    ]
                ];
            break;
        }
        return $data;
    }
}