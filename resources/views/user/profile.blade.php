 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <?php
    $user = Auth::user();
    ?>
    <?php
    $countryCallingCodes = array
    (
            93 => "Afghanistan",
            355 => "Albania",
            213 => "Algeria",
            1 => "American Samoa",
            376 => "Andorra",
            244 => "Angola",
            1 => "Anguilla",
            1 => "Antigua and Barbuda",
            54 => "Argentina",
            374 => "Armenia",
            297 => "Aruba",
            247 => "Ascension",
            61 => "Australia",
            43 => "Austria",
            994 => "Azerbaijan",
            1 => "Bahamas",
            973 => "Bahrain",
            880 => "Bangladesh",
            1 => "Barbados",
            375 => "Belarus",
            32 => "Belgium",
            501 => "Belize",
            229 => "Benin",
            1 => "Bermuda",
            975 => "Bhutan",
            591 => "Bolivia",
            387 => "Bosnia and Herzegovina",
            267 => "Botswana",
            55 => "Brazil",
            1 => "British Virgin Islands",
            673 => "Brunei",
            359 => "Bulgaria",
            226 => "Burkina Faso",
            257 => "Burundi",
            855 => "Cambodia",
            237 => "Cameroon",
            1 => "Canada",
            238 => "Cape Verde",
            1 => "Cayman Islands",
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
            1 => "Dominica",
            1 => "Dominican Republic",
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
            1 => "Grenada",
            590 => "Guadeloupe",
            1 => "Guam",
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
            1 => "Jamaica",
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
            1 => "Montserrat",
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
            1 => "Northern Marianas",
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
            1 => "Puerto Rico",
            974 => "Qatar",
            262 => "Reunion",
            40 => "Romania",
            7 => "Russian Federation",
            250 => "Rwanda",
            290 => "Saint Helena",
            1 => "Saint Kitts and Nevis",
            1 => "Saint Lucia",
            508 => "Saint Pierre and Miquelon",
            1 => "Saint Vincent and the Grenadines",
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
            1 => "Trinidad and Tobago",
            216 => "Tunisia",
            90 => "Turkey",
            993 => "Turkmenistan",
            1 => "Turks and Caicos Islands",
            688 => "Tuvalu",
            256 => "Uganda",
            380 => "Ukraine",
            971 => "United Arab Emirates",
            44 => "United Kingdom",
            1 => "United States",
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
    ?>
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">
                {{ $user->name }}
                <a href="{{ URL::to("logout") }}" class="pull-right btn btn-warning"><i class="fa fa-power-off"></i> Logout</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <div class="card section">
                    <div class="card-header">Personal Details <a onclick="$('#editprof').slideToggle('slow')" class="label label-info pull-right">Edit</a></div>
                    <div class="panel-body">
                        <div class="profile_pic">

                           <a onclick="$('#img_form').slideToggle()" href="#"><i class="fa fa-edit"></i><img src="@if(Auth::user()->image) {{ URL::to(Auth::user()->image) }} @else {{ URL::to('images/img.png') }} @endif" alt="..." class="img-circle profile_img"></a>
                        </div>
                        <form enctype="multipart/form-data" id="img_form" method="post" action="{{ URL::to('user/profile') }}" style="display:none;" class="form-horizontal">
                            <input type="hidden" name="_method" value="PUT">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-7">
                                    <input type="file" class="form-control" name="image">
                                     <button type="submit" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Save</button>

                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>E-mail</th>
                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ ucwords($user->country) }}</td>
                            </tr>
                            @if($user->role=='writer')
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($user->status==0)
                                            Inactive
                                        @elseif($user->suspended)
                                            <p style="color: red"><i class="fa fa-times"></i>Suspended </p>

                                        @else
                                            Active
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ @$user->writerCategory->name }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Phone</th>
                                <td>{{ ucwords($user->phone) }}</td>
                            </tr>
                            <tr>
                                <th colspan="2">Other Phone No.s</th>
                            </tr>
                            @foreach($user->phones as $phone)
                                <tr>
                                    <td>{{ $phone->phone }}</td>
                                    <td><button class="btn btn-danger btn-xs" onclick="deleteItem('{{ URL::to("user/profile/phone") }}',{{ $phone->id }})"><i class="fa fa-trash"></i> </button> </td>
                                </tr>
                                @endforeach
                            <tr>
                                <td colspan="2"><a href="#phone_modal" data-toggle="modal" class="btn btn-success"><i class="fa fa-plus"></i> Phone</a></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div id="editprof" style="display: {{ count($errors)<1 ? 'none;':';' }}" class="col-md-7">
                <div class="card section">
                    <div class="card-header">Update Profile</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('user/profile') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Name</label>

                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}">

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Phone</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="country">

                                        @foreach($countryCallingCodes as $key=>$val)
                                            <?php
                                            similar_text(explode('(',$user->country)[0],$val,$percent);
                                            $more = 90;
                                            if($percent > $more)
                                                $more=$percent
                                            ?>
                                            <option {{ $more>90 ? 'selected':''  }} value="{{ $val.'(+'.$key.')' }}">{{ $val.'(+'.$key.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Username</label>

                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="username" value="{{ $user->username }}">

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Password</label>

                                <div class="col-md-10">
                                    <input type="password" class="form-control" name="password">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-md-2 control-label">Confirm Password</label>

                                <div class="col-md-10">
                                    <input type="password" class="form-control" name="password_confirmation">

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-10 col-md-offset-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-check"></i>Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="phone_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <button class="btn btn-danger pull-right" data-dismiss="modal">&times;</button>
                                <h4>New Phone</h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("user/profile/phone") }}">
                               {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-3">
                                        Phone
                                    </label>
                                    <div class="col-md-7">
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">
                                        &nbsp;
                                    </label>
                                    <div class="col-md-7">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if(Auth::user()->role=='writer')
                <div class="row"></div>
                <div class="col-md-12">
                    <div class="card section">
                        <div class="card-header">
                            Performance
                        </div>
                        <div class="panel-body">
                            <div id="rating_gauge" class="col-md-3">

                            </div>
                            <div id="order_stats" class="col-md-9">

                            </div>
                        </div>
                    </div>
                </div>
                @include('user.graphs')
                @endif
        </div>
    </div>
@endsection