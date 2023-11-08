<?php
namespace AbcTravels\Admin\Param;

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
                    'passwordConfirm' => [
                        'method' => 'post',
                        'length' => [6,0],
                        'label' => 'Password Confirm',
                        'required' => true
                    ]
                ];
            break;

            case 'updatesettings':
                $data = [
                    'siteName' => [
                        'method' => 'post',
                        'length' => [3,250],
                        'label' => 'Site Name',
                        'required' => true
                    ],
                    'siteEmail' => [
                        'method' => 'post',
                        'length' => [13,150],
                        'label' => 'Site Email',
                        'required' => true
                    ],
                    'sitePhone' => [
                        'method' => 'post',
                        'length' => [8,17],
                        'label' => 'Site Phone',
                        'required' => true
                    ],
                    'siteAddress' => [
                        'method' => 'post',
                        'length' => [10,0],
                        'label' => 'Site Address',
                        'required' => true
                    ]
                ];
            break;

            case 'addtour':
            case 'updatetour':
                $data = [
                    'title' => [
                        'method' => 'post',
                        'length' => [3,250],
                        'label' => 'Title',
                        'required' => true
                    ],
                    'destinationId' => [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Destination',
                        'required' => true
                    ],
                    'numberOfDays' => [
                        'method' => 'post',
                        'length' => [1,3],
                        'label' => 'Number of Days',
                        'required' => true
                    ],
                    'price' => [
                        'method' => 'post',
                        'length' => [2,6],
                        'label' => 'Price',
                        'required' => true
                    ],
                    'specialPackage' => [
                        'method' => 'post',
                        'length' => [1,1],
                        'label' => 'Is Special Package',
                        'required' => false
                    ],
                    'mapIframe' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Map Iframe',
                        'required' => false
                    ],
                    'inclusions' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Inclusions',
                        'required' => false
                    ],
                    'summary' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Summary',
                        'required' => false
                    ]
                ];
                if ($action == 'updatetour')
                {
                    $data['id'] = [
                        'method' => 'post',
                        'length' => [36,36],
                        'label' => 'Tour',
                        'required' => true
                    ];
                }
            break;

            case 'addvehicle':
            case 'updatevehicle':
                $data = [
                    'name' => [
                        'method' => 'post',
                        'length' => [3,100],
                        'label' => 'Vehicle Name',
                        'required' => true
                    ],
                    'passengers' => [
                        'method' => 'post',
                        'length' => [1,250],
                        'label' => 'No. of Passengers',
                        'required' => true
                    ]
                ];
            break;
            
            case 'updateterms':
                $data = [
                    'privacyPolicy' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Privacy Policy',
                        'required' => false
                    ],
                    'taxiBookings' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Taxi Bookings',
                        'required' => false
                    ],
                    'trainReservations' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Train Reservations',
                        'required' => false
                    ],
                    'safariReservations' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Safari Reservations',
                        'required' => false
                    ],
                    'tourReservations' => [
                        'method' => 'post',
                        'length' => [0,0],
                        'label' => 'Tour Reservations',
                        'required' => false
                    ]
                ];
            break;
        }
        return $data;
    }
}