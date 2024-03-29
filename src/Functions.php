<?php
namespace AbcTravels;

use AbcTravels\Destination\Destination;

class Functions
{
    public static function getToursSearchForm()
    {
        $tourCountry = isset($_GET['tourCountry']) ? trim($_GET['tourCountry']) : '';
        $tourDuration = isset($_GET['tourDuration']) ? doTypeCastInt($_GET['tourDuration']) : 0;

        $destinationsOptions = Destination::getDestinationsDropdownOptions($tourCountry, true);
        $tourDaysDropdownOptions = self::getTourDaysDropdownOptions($tourDuration);

        return <<<EOQ
        <div class="brand-slider-area style-1">
            <div class="container">
                <div class="location-filter-wrapper style-2">
                    <div class="location-filter-card">
                        <form method="GET" action="tour">
                            <input type="hidden" name="action" value="searchtour">
                            <div class="single-item">
                                <label for="state" class="select-location">Tour Country</label>
                                <select id="tourCountry" name="tourCountry">
                                    {$destinationsOptions}
                                </select>
                            </div>
                            <div class="single-item">
                                <label for="state" class="select-location">Tour Duration</label>
                                <select id="tourDuration" name="tourDuration">
                                    {$tourDaysDropdownOptions}
                                </select>
                            </div>
                            <button class="style-1" type="submit" id="btnSubmit"><i class="icon-search"></i>Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
EOQ;
    }

    public static function getTourDaysDropdownOptions($tourDays=0)
    {
        $start = 3;
        $end = 15;
        $options = <<<EOQ
        <option value=""></option>
EOQ;
        for($i=$start; $i<=$end; $i++)
        {
            $selected = '';
            if (doTypeCastInt($tourDays) > 0)
            {
                if ($i == $tourDays)
                {
                    $selected = 'selected';
                }
            }
            else
            {
                if ($i == $start)
                {
                    $selected = 'selected';
                }
            }
            
            $options .= <<<EOQ
            <option value="{$i}" {$selected}>{$i} Days</option>
EOQ;
        }
            return $options;
        }

    public static function getCommonEnquiryForm($tourId='', $tourDestination=0)
    {
        $destinationsOptions = Destination::getDestinationsDropdownOptions();
        $countriesDropdownOptions = self::getCountriesDropdownOptions();
        $minDate = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
        $termsAndConditions = self::getTermsAndConditionCheckbox();

        return <<<EOQ
        <form class="row g-3" id="commonEnquiryForm" onsubmit="return false;">
            <input type="hidden" name="action" value="addCommonEnquiry">
            <input type="hidden" name="tourId" value="{$tourId}">
            <input type="hidden" name="tourDestination" value="{$tourDestination}">
            <div class="col-md-6">
                <label for="name" class="form-label">Name</label>
                <input type="name" class="form-control" id="name" name="name">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile</label>
                <input type="tel" class="form-control" id="mobile" name="mobile">
            </div>
            <div class="col-md-6">
                <label for="nationality" class="form-label">Nationality</label>
                <select id="nationality" name="nationality" class="form-select">
                    {$countriesDropdownOptions}
                </select>
            </div>
            <div class="col-md-6">
                <label for="arrivalDate" class="form-label">Arrival Date</label>
                <input type="date" class="form-control" id="arrivalDate" name="arrivalDate" min="{$minDate}">
            </div>
            <div class="col-md-6">
                <label for="departureDate" class="form-label">Departure Date</label>
                <input type="date" class="form-control" id="departureDate" name="departureDate" min="{$minDate}">
            </div>
            <div class="col-md-12">
                <label for="destination" class="form-label">Destination</label>
                <select id="destination" name="destination" class="form-select">
                    {$destinationsOptions}
                </select>
            </div>
            <label for="travellers" class="form-label">No. of Travellers</label>
            <div class="col-md-6 pt-1">
                <input type="number" class="form-control" id="numAdult" name="numAdult" placeholder="Adult">
            </div>
            <div class="col-md-6 pt-1">
                <input type="number" class="form-control" id="numChildren" name="numChildren" placeholder="Children">
            </div>
            <div class="col-md-12">
                <label for="childrenAges" class="form-label">Children Ages (separate with commas)</label>
                <input type="text" class="form-control" id="childrenAges" name="childrenAges" placeholder="e.g 7, 10, 13">
            </div>
            <div class="col-md-12">
                <label for="message" class="form-label">Your Message</label>
                <textarea class="form-control" name="message" id="message" cols="30" rows="4"></textarea>
            </div>
            <div class="col-md-12">
                {$termsAndConditions}
            </div>
            <div class="col-md-12">
                <div class="googleRecaptcha" id="commonEnquiryFormRecaptcha"></div>
            </div>
            <div class="col-12">
                <button type="submit" class="theme-btn" id="btnSubmit">Submit</button>
            </div>
        </form>
EOQ;
    }

    public static function getTermsAndConditionCheckbox()
    {
        $termsAndConditionsLink = DEF_FULL_ROOT_PATH . '/terms';
        return <<<EOQ
        <input style="display:inline-block;width:5%;" type="checkbox" class="form-control" id="termsConditions" name="termsConditions">
        <label style="display:inline-block;width:92%;">By clicking here, I state that I have read and understood the terms and conditions. <a href="{$termsAndConditionsLink}" target="_blank">Read it here.</a></label>
EOQ;
    }

    public static function getEnquireNowModal($action='addTourEnquiry', $tourId='', $tourDestination=0)
    {
        $minDate = date('Y-m-d', strtotime('+1 days', strtotime(date('Y-m-d'))));
        $countriesDropdown = self::getCountriesDropdownOptions();
        $destinationsDropdown = Destination::getDestinationsDropdownOptions();
        $termsAndConditions = self::getTermsAndConditionCheckbox();

        return <<<EOQ
<div class="modal fade" id="enquireNowModal" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="enquireNowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enquire Now</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="row g-3" id="tourEnquiryForm" onsubmit="return false;">
                <input type="hidden" name="action" value="{$action}">
                <input type="hidden" id="tourId" name="tourId" value="{$tourId}">
                <input type="hidden" id="tourDestination" name="tourDestination" value="{$tourDestination}">
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="name" class="form-control" id="name" name="name">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="col-md-6">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile">
                </div>
                <div class="col-md-6">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select id="nationality" name="nationality" class="form-select">
                    {$countriesDropdown}
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="arrival-date" class="form-label">Arrival Date</label>
                    <input type="date" class="form-control" id="arrivalDate" name="arrivalDate" min="{$minDate}">
                </div>
                <div class="col-md-6">
                    <label for="departure-date" class="form-label">Departure Date</label>
                    <input type="date" class="form-control" id="departureDate" name="departureDate" min="{$minDate}">
                </div>
                <div class="col-md-12">
                    <label for="destination" class="form-label">Destination</label>
                    <select id="destination" name="destination" class="form-select">
                        {$destinationsDropdown}
                    </select>
                </div>
                <label for="travellers" class="form-label">No. of Travellers</label>
                <div class="col-md-6">
                    <input type="number" class="form-control" id="numAdult" name="numAdult" placeholder="Adult">
                </div>
                <div class="col-md-6">
                    <input type="number" class="form-control" id="numChildren" name="numChildren" placeholder="Children">
                </div>
                <div class="col-md-12">
                    <label for="childrenAges" class="form-label">Children Ages (separate with commas)</label>
                    <input type="text" class="form-control" id="childrenAges" name="childrenAges" placeholder="e.g 7, 10, 13">
                </div>
                <div class="col-md-12">
                    <label for="destination" class="form-label">Message</label>
                    <textarea class="form-control" name="message" id="message" cols="30" rows="4"></textarea>
                </div>
                <div class="col-md-12">
                    {$termsAndConditions}
                </div>
                <div class="col-md-12">
                    <div class="googleRecaptcha" id="tourFormRecaptcha"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <button type="button" class="theme-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="theme-btn" onclick="invokeTourEnquiryFormProcess()">Submit</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
EOQ;
    }

    public static function getCountriesDropdownOptions()
    {
        return <<<EOQ
        <option value="Afghanistan">Afghanistan</option>
        <option value="Albania">Albania</option>
        <option value="Algeria">Algeria</option>
        <option value="American Samoa">American Samoa</option>
        <option value="Andorra">Andorra</option>
        <option value="Angola">Angola</option>
        <option value="Anguilla">Anguilla</option>
        <option value="Antartica">Antarctica</option>
        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
        <option value="Argentina">Argentina</option>
        <option value="Armenia">Armenia</option>
        <option value="Aruba">Aruba</option>
        <option value="Australia">Australia</option>
        <option value="Austria">Austria</option>
        <option value="Azerbaijan">Azerbaijan</option>
        <option value="Bahamas">Bahamas</option>
        <option value="Bahrain">Bahrain</option>
        <option value="Bangladesh">Bangladesh</option>
        <option value="Barbados">Barbados</option>
        <option value="Belarus">Belarus</option>
        <option value="Belgium">Belgium</option>
        <option value="Belize">Belize</option>
        <option value="Benin">Benin</option>
        <option value="Bermuda">Bermuda</option>
        <option value="Bhutan">Bhutan</option>
        <option value="Bolivia">Bolivia</option>
        <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
        <option value="Botswana">Botswana</option>
        <option value="Bouvet Island">Bouvet Island</option>
        <option value="Brazil">Brazil</option>
        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
        <option value="Brunei Darussalam">Brunei Darussalam</option>
        <option value="Bulgaria">Bulgaria</option>
        <option value="Burkina Faso">Burkina Faso</option>
        <option value="Burundi">Burundi</option>
        <option value="Cambodia">Cambodia</option>
        <option value="Cameroon">Cameroon</option>
        <option value="Canada">Canada</option>
        <option value="Cape Verde">Cape Verde</option>
        <option value="Cayman Islands">Cayman Islands</option>
        <option value="Central African Republic">Central African Republic</option>
        <option value="Chad">Chad</option>
        <option value="Chile">Chile</option>
        <option value="China">China</option>
        <option value="Christmas Island">Christmas Island</option>
        <option value="Cocos Islands">Cocos (Keeling) Islands</option>
        <option value="Colombia">Colombia</option>
        <option value="Comoros">Comoros</option>
        <option value="Congo">Congo</option>
        <option value="Congo">Congo, the Democratic Republic of the</option>
        <option value="Cook Islands">Cook Islands</option>
        <option value="Costa Rica">Costa Rica</option>
        <option value="Cota D'Ivoire">Cote d'Ivoire</option>
        <option value="Croatia">Croatia (Hrvatska)</option>
        <option value="Cuba">Cuba</option>
        <option value="Cyprus">Cyprus</option>
        <option value="Czech Republic">Czech Republic</option>
        <option value="Denmark">Denmark</option>
        <option value="Djibouti">Djibouti</option>
        <option value="Dominica">Dominica</option>
        <option value="Dominican Republic">Dominican Republic</option>
        <option value="East Timor">East Timor</option>
        <option value="Ecuador">Ecuador</option>
        <option value="Egypt">Egypt</option>
        <option value="El Salvador">El Salvador</option>
        <option value="Equatorial Guinea">Equatorial Guinea</option>
        <option value="Eritrea">Eritrea</option>
        <option value="Estonia">Estonia</option>
        <option value="Ethiopia">Ethiopia</option>
        <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
        <option value="Faroe Islands">Faroe Islands</option>
        <option value="Fiji">Fiji</option>
        <option value="Finland">Finland</option>
        <option value="France">France</option>
        <option value="France Metropolitan">France, Metropolitan</option>
        <option value="French Guiana">French Guiana</option>
        <option value="French Polynesia">French Polynesia</option>
        <option value="French Southern Territories">French Southern Territories</option>
        <option value="Gabon">Gabon</option>
        <option value="Gambia">Gambia</option>
        <option value="Georgia">Georgia</option>
        <option value="Germany">Germany</option>
        <option value="Ghana">Ghana</option>
        <option value="Gibraltar">Gibraltar</option>
        <option value="Greece">Greece</option>
        <option value="Greenland">Greenland</option>
        <option value="Grenada">Grenada</option>
        <option value="Guadeloupe">Guadeloupe</option>
        <option value="Guam">Guam</option>
        <option value="Guatemala">Guatemala</option>
        <option value="Guinea">Guinea</option>
        <option value="Guinea-Bissau">Guinea-Bissau</option>
        <option value="Guyana">Guyana</option>
        <option value="Haiti">Haiti</option>
        <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
        <option value="Holy See">Holy See (Vatican City State)</option>
        <option value="Honduras">Honduras</option>
        <option value="Hong Kong">Hong Kong</option>
        <option value="Hungary">Hungary</option>
        <option value="Iceland">Iceland</option>
        <option value="India">India</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Iran">Iran (Islamic Republic of)</option>
        <option value="Iraq">Iraq</option>
        <option value="Ireland">Ireland</option>
        <option value="Israel">Israel</option>
        <option value="Italy">Italy</option>
        <option value="Jamaica">Jamaica</option>
        <option value="Japan">Japan</option>
        <option value="Jordan">Jordan</option>
        <option value="Kazakhstan">Kazakhstan</option>
        <option value="Kenya">Kenya</option>
        <option value="Kiribati">Kiribati</option>
        <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
        <option value="Korea">Korea, Republic of</option>
        <option value="Kuwait">Kuwait</option>
        <option value="Kyrgyzstan">Kyrgyzstan</option>
        <option value="Lao">Lao People's Democratic Republic</option>
        <option value="Latvia">Latvia</option>
        <option value="Lebanon">Lebanon</option>
        <option value="Lesotho">Lesotho</option>
        <option value="Liberia">Liberia</option>
        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
        <option value="Liechtenstein">Liechtenstein</option>
        <option value="Lithuania">Lithuania</option>
        <option value="Luxembourg">Luxembourg</option>
        <option value="Macau">Macau</option>
        <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
        <option value="Madagascar">Madagascar</option>
        <option value="Malawi">Malawi</option>
        <option value="Malaysia">Malaysia</option>
        <option value="Maldives">Maldives</option>
        <option value="Mali">Mali</option>
        <option value="Malta">Malta</option>
        <option value="Marshall Islands">Marshall Islands</option>
        <option value="Martinique">Martinique</option>
        <option value="Mauritania">Mauritania</option>
        <option value="Mauritius">Mauritius</option>
        <option value="Mayotte">Mayotte</option>
        <option value="Mexico">Mexico</option>
        <option value="Micronesia">Micronesia, Federated States of</option>
        <option value="Moldova">Moldova, Republic of</option>
        <option value="Monaco">Monaco</option>
        <option value="Mongolia">Mongolia</option>
        <option value="Montserrat">Montserrat</option>
        <option value="Morocco">Morocco</option>
        <option value="Mozambique">Mozambique</option>
        <option value="Myanmar">Myanmar</option>
        <option value="Namibia">Namibia</option>
        <option value="Nauru">Nauru</option>
        <option value="Nepal">Nepal</option>
        <option value="Netherlands">Netherlands</option>
        <option value="Netherlands Antilles">Netherlands Antilles</option>
        <option value="New Caledonia">New Caledonia</option>
        <option value="New Zealand">New Zealand</option>
        <option value="Nicaragua">Nicaragua</option>
        <option value="Niger">Niger</option>
        <option value="Nigeria">Nigeria</option>
        <option value="Niue">Niue</option>
        <option value="Norfolk Island">Norfolk Island</option>
        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
        <option value="Norway">Norway</option>
        <option value="Oman">Oman</option>
        <option value="Pakistan">Pakistan</option>
        <option value="Palau">Palau</option>
        <option value="Panama">Panama</option>
        <option value="Papua New Guinea">Papua New Guinea</option>
        <option value="Paraguay">Paraguay</option>
        <option value="Peru">Peru</option>
        <option value="Philippines">Philippines</option>
        <option value="Pitcairn">Pitcairn</option>
        <option value="Poland">Poland</option>
        <option value="Portugal">Portugal</option>
        <option value="Puerto Rico">Puerto Rico</option>
        <option value="Qatar">Qatar</option>
        <option value="Reunion">Reunion</option>
        <option value="Romania">Romania</option>
        <option value="Russia">Russian Federation</option>
        <option value="Rwanda">Rwanda</option>
        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
        <option value="Saint LUCIA">Saint LUCIA</option>
        <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
        <option value="Samoa">Samoa</option>
        <option value="San Marino">San Marino</option>
        <option value="Sao Tome and Principe">Sao Tome and Principe</option> 
        <option value="Saudi Arabia">Saudi Arabia</option>
        <option value="Senegal">Senegal</option>
        <option value="Seychelles">Seychelles</option>
        <option value="Sierra">Sierra Leone</option>
        <option value="Singapore">Singapore</option>
        <option value="Slovakia">Slovakia (Slovak Republic)</option>
        <option value="Slovenia">Slovenia</option>
        <option value="Solomon Islands">Solomon Islands</option>
        <option value="Somalia">Somalia</option>
        <option value="South Africa">South Africa</option>
        <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
        <option value="Span">Spain</option>
        <option value="SriLanka">Sri Lanka</option>
        <option value="St. Helena">St. Helena</option>
        <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
        <option value="Sudan">Sudan</option>
        <option value="Suriname">Suriname</option>
        <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
        <option value="Swaziland">Swaziland</option>
        <option value="Sweden">Sweden</option>
        <option value="Switzerland">Switzerland</option>
        <option value="Syria">Syrian Arab Republic</option>
        <option value="Taiwan">Taiwan, Province of China</option>
        <option value="Tajikistan">Tajikistan</option>
        <option value="Tanzania">Tanzania, United Republic of</option>
        <option value="Thailand">Thailand</option>
        <option value="Togo">Togo</option>
        <option value="Tokelau">Tokelau</option>
        <option value="Tonga">Tonga</option>
        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
        <option value="Tunisia">Tunisia</option>
        <option value="Turkey">Turkey</option>
        <option value="Turkmenistan">Turkmenistan</option>
        <option value="Turks and Caicos">Turks and Caicos Islands</option>
        <option value="Tuvalu">Tuvalu</option>
        <option value="Uganda">Uganda</option>
        <option value="Ukraine">Ukraine</option>
        <option value="United Arab Emirates">United Arab Emirates</option>
        <option value="United Kingdom">United Kingdom</option>
        <option value="United States">United States</option>
        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
        <option value="Uruguay">Uruguay</option>
        <option value="Uzbekistan">Uzbekistan</option>
        <option value="Vanuatu">Vanuatu</option>
        <option value="Venezuela">Venezuela</option>
        <option value="Vietnam">Viet Nam</option>
        <option value="Virgin Islands (British)">Virgin Islands (British)</option>
        <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
        <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
        <option value="Western Sahara">Western Sahara</option>
        <option value="Yemen">Yemen</option>
        <option value="Serbia">Serbia</option>
        <option value="Zambia">Zambia</option>
        <option value="Zimbabwe">Zimbabwe</option>
EOQ;
    }

    public static function getYesOrNoDropdown($selected)
    {
        $selectedZero = $selectedOne = '';
        switch($selected)
        {
            case 0:
                $selectedZero = 'selected';
            break;
            case 1:
                $selectedOne = 'selected';
            break;
            default:
                $selectedZero = 'selected';
            break;
        }
        return <<<EOQ
        <option value="1" {$selectedOne}>Yes</option>
        <option value="0" {$selectedZero}>No</option>
EOQ;
    }
}