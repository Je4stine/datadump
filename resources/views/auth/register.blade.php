@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
            $web_repo = new \App\Repositories\WebsiteRepository();
            $website = $web_repo->getWebsite();
    $countryCallingCodes = array
    (
            '+1'=>'USA',
            93 => "Afghanistan",
            355 => "Albania",
            213 => "Algeria",
            376 => "Andorra",
            244 => "Angola",
            54 => "Argentina",
            374 => "Armenia",
            297 => "Aruba",
            247 => "Ascension",
            61 => "Australia",
            43 => "Austria",
            994 => "Azerbaijan",
            973 => "Bahrain",
            880 => "Bangladesh",
            375 => "Belarus",
            32 => "Belgium",
            501 => "Belize",
            229 => "Benin",
            '++1' => "Bermuda",
            975 => "Bhutan",
            591 => "Bolivia",
            387 => "Bosnia and Herzegovina",
            267 => "Botswana",
            55 => "Brazil",
            '+++1' => "British Virgin Islands",
            673 => "Brunei",
            359 => "Bulgaria",
            226 => "Burkina Faso",
            257 => "Burundi",
            855 => "Cambodia",
            237 => "Cameroon",
            '++++1' => "Canada",
            238 => "Cape Verde",
            '+++++1' => "Cayman Islands",
            236 => "Central African Republic",
            235 => "Chad",
            56 => "Chile",
            86 => "China",
            57 => "Colombia",
            269 => "Comoros",
            242 => "Congo",
            682 => "Cook Islands",
            506 => "Costa Rica",
            385 => "Croatia",
            53 => "Cuba",
            357 => "Cyprus",
            420 => "Czech Republic",
            243 => "Democratic Republic of Congo",
            45 => "Denmark",
            246 => "Diego Garcia",
            253 => "Djibouti",
            '++++++1' => "Dominica",
            '+++++++1' => "Dominican Republic",
            670 => "East Timor",
            593 => "Ecuador",
            20 => "Egypt",
            503 => "El Salvador",
            240 => "Equatorial Guinea",
            291 => "Eritrea",
            372 => "Estonia",
            251 => "Ethiopia",
            500 => "Falkland (Malvinas) Islands",
            298 => "Faroe Islands",
            679 => "Fiji",
            358 => "Finland",
            33 => "France",
            594 => "French Guiana",
            689 => "French Polynesia",
            241 => "Gabon",
            220 => "Gambia",
            995 => "Georgia",
            49 => "Germany",
            233 => "Ghana",
            350 => "Gibraltar",
            30 => "Greece",
            299 => "Greenland",
            '++++++++1' => "Grenada",
            590 => "Guadeloupe",
            '+++++++++1' => "Guam",
            502 => "Guatemala",
            224 => "Guinea",
            245 => "Guinea-Bissau",
            592 => "Guyana",
            509 => "Haiti",
            504 => "Honduras",
            852 => "Hong Kong",
            36 => "Hungary",
            354 => "Iceland",
            91 => "India",
            62 => "Indonesia",
            870  => "Inmarsat Satellite",
            98 => "Iran",
            964 => "Iraq",
            353 => "Ireland",
            972 => "Israel",
            39 => "Italy",
            225 => "Ivory Coast",
            '+++++++++++1' => "Jamaica",
            81 => "Japan",
            962 => "Jordan",
            7 => "Kazakhstan",
            254 => "Kenya",
            686 => "Kiribati",
            965 => "Kuwait",
            996 => "Kyrgyzstan",
            856 => "Laos",
            371 => "Latvia",
            961 => "Lebanon",
            266 => "Lesotho",
            231 => "Liberia",
            218 => "Libya",
            423 => "Liechtenstein",
            370 => "Lithuania",
            352 => "Luxembourg",
            853 => "Macau",
            389 => "Macedonia",
            261 => "Madagascar",
            265 => "Malawi",
            60 => "Malaysia",
            960 => "Maldives",
            223 => "Mali",
            356 => "Malta",
            692 => "Marshall Islands",
            596 => "Martinique",
            222 => "Mauritania",
            230 => "Mauritius",
            262 => "Mayotte",
            52 => "Mexico",
            691 => "Micronesia",
            373 => "Moldova",
            377 => "Monaco",
            976 => "Mongolia",
            382 => "Montenegro",
            "++++++++++++1" => "Montserrat",
            212 => "Morocco",
            258 => "Mozambique",
            95 => "Myanmar",
            264 => "Namibia",
            674 => "Nauru",
            977 => "Nepal",
            31 => "Netherlands",
            599 => "Netherlands Antilles",
            687 => "New Caledonia",
            64 => "New Zealand",
            505 => "Nicaragua",
            227 => "Niger",
            234 => "Nigeria",
            683 => "Niue Island",
            850 => "North Korea",
            "+++++++++++++1" => "Northern Marianas",
            47 => "Norway",
            968 => "Oman",
            92 => "Pakistan",
            680 => "Palau",
            507 => "Panama",
            675 => "Papua New Guinea",
            595 => "Paraguay",
            51 => "Peru",
            63 => "Philippines",
            48 => "Poland",
            351 => "Portugal",
            '-1' => "Puerto Rico",
            974 => "Qatar",
            262 => "Reunion",
            40 => "Romania",
            7 => "Russian Federation",
            250 => "Rwanda",
            290 => "Saint Helena",
            '--1' => "Saint Kitts and Nevis",
            '---1' => "Saint Lucia",
            508 => "Saint Pierre and Miquelon",
            '----1' => "Saint Vincent and the Grenadines",
            685 => "Samoa",
            378 => "San Marino",
            239 => "Sao Tome and Principe",
            966 => "Saudi Arabia",
            221 => "Senegal",
            381 => "Serbia",
            248 => "Seychelles",
            232 => "Sierra Leone",
            65 => "Singapore",
            421 => "Slovakia",
            386 => "Slovenia",
            677 => "Solomon Islands",
            252 => "Somalia",
            27 => "South Africa",
            82 => "South Korea",
            34 => "Spain",
            94 => "Sri Lanka",
            249 => "Sudan",
            597 => "Suriname",
            268 => "Swaziland",
            46 => "Sweden",
            41 => "Switzerland",
            963 => "Syria",
            886 => "Taiwan",
            992 => "Tajikistan",
            255 => "Tanzania",
            66 => "Thailand",
            228 => "Togo",
            690 => "Tokelau",
            '----1' => "Trinidad and Tobago",
            216 => "Tunisia",
            90 => "Turkey",
            993 => "Turkmenistan",
            '------1' => "Turks and Caicos Islands",
            688 => "Tuvalu",
            256 => "Uganda",
            380 => "Ukraine",
            971 => "United Arab Emirates",
            44 => "United Kingdom",
            '-------1' => "United States of America",
            '--------1' => "U.S. Virgin Islands",
            598 => "Uruguay",
            998 => "Uzbekistan",
            678 => "Vanuatu",
            379 => "Vatican City",
            58 => "Venezuela",
            84 => "Vietnam",
            681 => "Wallis and Futuna",
            967 => "Yemen",
            260 => "Zambia",
            263 => "Zimbabwe"
    );

$ip_api = "http://www.geoplugin.net/json.gp?ip=".Request::ip();
        $country = @json_decode(@file_get_contents(@$ip_api))->geoplugin_countryName;
    ?>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <div class="container">
        <div class="row">
            <div class="">
                <div class="card section">
                    <div class="card-header">Register</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('user/register') }}">
                            {!! csrf_field() !!}
                          <input type="hidden" name="role" value="{{ $website->role }}">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Phone</label>
                                <div class="col-md-2">
                                    <select class="form-control" name="country">

                                        @foreach($countryCallingCodes as $key=>$val)
                                            <?php
                                            $key = str_replace('-','',$key);
                                            $key = str_replace('+','',$key);
                                            similar_text($country,$val,$percent);
                                            $more = 90;
                                            if($percent > $more)
                                                $more=$percent

                                            ?>
                                            <option {{ $more>90 ? 'selected':''  }} value="{{ $val.'(+'.$key.')' }}">{{ $val.'(+'.$key.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="phone">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Confirm Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password_confirmation">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('robot') ? ' has-error' : '' }}">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="g-recaptcha" data-sitekey="6LeGZSETAAAAALP7G0Nzrh84ntS6KTgy6DDkjhMH"></div>                                    @if ($errors->has('robot'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('robot') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-user-plus"></i>Sign Up
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
//        var telInput = $("input[name='phoddne']");
//        telInput.intlTelInput();
//        telInput.change(function(){
//            checkValid();
//        });
        function setCountry(){
            var countryData = $("input[name='phone']").intlTelInput("getSelectedCountryData");
            var country = countryData.name+"(+"+countryData.dialCode+")";
            $("input[name='country']").val(country);
        }
        $("#reg_form").submit(function(){
            checkValid();
        });
        function checkValid(){
            setCountry();
//       var phone = $("input[name='phone']").val();
//        if(isNaN(phone) && phone.length<15 && phone.length>6){
//            isValid = true;
//        }else{
//            isValid = false;
//        }
//
//        if(isValid){
//            console.log('valid');
//            $("#phone_group").removeClass('has-error');
//        }else{
//            $("#phone_group").addClass('has-error');
//            console.log('invalid');
//        }
//        return isValid;
        }
    </script>
        <style type="text/css">
        /*
  Hide radio button (the round disc)
  we will use just the label to create pushbutton effect
*/
        input[type=radio] {
            display:none;
            margin:10px;
        }

        /*
          Change the look'n'feel of labels (which are adjacent to radiobuttons).
          Add some margin, padding to label
        */
        input[type=radio] + label {
            display:inline-block;
            margin:-2px;
            padding: 4px 12px;
            background-color: #e7e7e7;
            border-color: #ddd;
        }
        /*
         Change background color for label next to checked radio button
         to make it look like highlighted button
        */
        input[type=radio]:checked + label {
            background-image: none;
            background-color:#d0d0d0;
        }
        div + p {
            color: red;
        }

        input[type=radio] {
            display:none;
        }

        input[type=radio] + label {
            display:inline-block;
            margin:-2px;
            padding: 4px 12px;
            margin-bottom: 0;
            font-size: 14px;
            line-height: 20px;
            color: #333;
            text-align: center;
            text-shadow: 0 1px 1px rgba(255,255,255,0.75);
            vertical-align: middle;
            cursor: pointer;
            background-color: #f5f5f5;
            background-image: -moz-linear-gradient(top,#fff,#e6e6e6);
            background-image: -webkit-gradient(linear,0 0,0 100%,from(#fff),to(#e6e6e6));
            background-image: -webkit-linear-gradient(top,#fff,#e6e6e6);
            background-image: -o-linear-gradient(top,#fff,#e6e6e6);
            background-image: linear-gradient(to bottom,#fff,#e6e6e6);
            background-repeat: repeat-x;
            border: 1px solid #ccc;
            border-color: #e6e6e6 #e6e6e6 #bfbfbf;
            border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
            border-bottom-color: #b3b3b3;
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
            filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
            -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
            -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.2),0 1px 2px rgba(0,0,0,0.05);
        }

        input[type=radio]:checked + label {
            background-image: none;
            outline: 0;
            -webkit-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
            -moz-box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.15),0 1px 2px rgba(0,0,0,0.05);
            background-color:#e0e0e0;
        }
    </style>

@endsection
