@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                {{ $user->name }}
                @if($user->role=='writer')
                    <div class="pull-right">
                        @if(Auth::user()->isAllowedTo('writer_application_info'))  <a class="btn btn-info btn-sm" href="{{ URL::to("user/$user->id/application_info") }}"><i class="fa fa-info"></i> Application Info</a> @endif
                        @if(Auth::user()->isAllowedTo('writer_payments')) <a class="btn btn-success btn-sm" href="{{ URL::to("user/$user->id/payments") }}"><i class="fa fa-money"></i> Payments</a> @endif
                        @if(!$user->suspended)
                            @if(Auth::user()->isAllowedTo('suspend_writer')) <a class="btn btn-danger btn-sm" href="{{ URL::to("user/$user->id/suspend") }}"><i class="fa fa-money"></i> Suspend User</a> @endif
                        @endif
                        @if(\App\Website::where('designer',1)->count())
                            @if(!$user->isDesigner())
                            <a onclick="runPlainRequest('{{ URL::to('user/view/writer/allow-designer') }}',{{ $user->id }},'Allow writer to view/bid and work on orders from designer website(s)')" class="btn btn-primary"><i class="fa fa-check"></i> Allow Designer</a>
                                @else
                                    <a onclick="runPlainRequest('{{ URL::to('user/view/writer/allow-designer') }}',{{ $user->id }},'Disable writer from viewing/bidding and working on orders from designer website(s)')" class="btn btn-warning"><i class="fa fa-times"></i> Disable Designer</a>
                                @endif
                         @endif
                    </div>
                 @endif
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Personal Details <a href="#user_edit_modal" data-toggle="modal" class="btn btn-info btn-sm pull-right">Edit</a> </div>
                    <div class="panel-body">
                        <div class="profile_pic">

                          <img src="@if($user->image) {{ URL::to($user->image) }} @else {{ URL::to('images/img.png') }} @endif " alt="..." class="img-circle profile_img">
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            @if(Auth::user()->isAllowedTo('view_email'))
                            <tr>
                                <th>E-mail</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Send Email</th>
                                <td>
                                    <form action="{{ URL::to("emails/send") }}">
                                        <input type="hidden" name="role" value="{{ $user->role }}">
                                        <input type="hidden" name="user_ids[]" value="{{ $user->id }}">
                                        <button type="submit"><i class="fa fa-envelope"></i> Email</button>
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ ucwords($user->role) }}</td>
                            </tr>
                            <tr>
                                <th>Author</th>
                                <td>
                                    @if($user->is_author)
                                        <label class="label label-info">Yes</label>
                                        <button class="btn btn-warning btn-xs" onclick="runPlainRequest('{{ url("user/view/$user->role") }}',{{ $user->id }})">Revoke Author</button>
                                @else
                                        <label class="label label-warning">No</label>
                                        <button class="btn btn-info btn-xs" onclick="runPlainRequest('{{ url("user/view/$user->role") }}',{{ $user->id }})">Make Author</button>

                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ ucwords($user->country) }}</td>
                            </tr>
                            @if(Auth::user()->isAllowedTo('view_phone'))
                            <tr>
                                <th>Phone</th>
                                <td>{{ ucwords($user->phone) }}</td>
                            </tr>
                            @endif
                            @if($user->role=='client')
                            <tr>
                                <th>Total Orders</th>
                                <td>{{ $user->orders()->count()  }}<a class="btn btn-info btn-xs pull-right" href="{{ URL::to("user/$user->id/orders") }}"><i class="fa fa-eye"></i> View</a> </td>
                            </tr>
                            @endif
                            @if($user->website)
                                <tr>
                                    <th>Website</th>
                                    <td>{{ $user->website->name  }} </td>
                                </tr>
                            @endif
                            @if($user->role=='writer')
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($user->status==0)
                                            Inactive
                                        @elseif($user->suspended)
                                            <p style="color: red"><i class="fa fa-times"></i>Suspended </p>
                                            <a onclick="return confirm('Are you sure?');" href="{{ URL::to("user/$user->id/activate") }}" class="btn btn-success pull-right btn-xs"><i class="fa fa-check"></i> Activate</a>

                                        @else
                                            Active
                                        @endif
                                    </td>

                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ @$user->writerCategory->name }}<a href="#category_modal" data-toggle="modal" class="pull-right label label-info"><i class="fa fa-edit"></i> Edit</a> </td>
                                </tr>
                                @endif

                                <tr>
                                    <th>Last Login</th>
                                    <?php
                                    $login = $user->devices()->orderBy('updated_at','desc')->first();
                                    // dd($login);
                                        if(!$login){
                                            $updated = $user->updated_at;
                                        }else{
                                            $updated = $login->updated_at;
                                        }
                                    $last_login = \Carbon\Carbon::createFromTimestamp(strtotime($updated));
                                    $login_time = $last_login->diffForHumans();
                                    ?>
                                    <td>{{  $login_time }}</td>
                                </tr>
                            <tr>
                                <td colspan="2"><a href="#custom_fields_modal"></a> </td>
                            </tr>

                        </table>
                    </div>
                </div>
                </div>
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">User Traits <a href="{{ URL::to("user/$user->id/add_trait") }}" class="pull-right btn btn-info"><i class="fa fa-plus"></i> ADD</a> </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>#</th>
                                <th>Trait</th>
                                <th>Description</th>
                                <th>On</th>
                                <th>Action</th>
                            </tr>
                            @foreach($traits = $user->traits()->orderBy('id','desc')->paginate(10) as $trait)
                                <tr>
                                    <td>{{ $trait->id }}</td>
                                    <td>{{ $trait->trait }}</td>
                                    <td>{{ $trait->description }}</td>
                                    <td>{{ date('Y M d',strtotime($trait->created_at)) }}</td>
                                    <td>
                                        <a href="{{ URL::to("user/$user->id/edit_trait/$trait->id") }}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            @if($user->website->wallet && $user->role=='client')
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">User E-Wallet </div>
                    <div class="panel-body">
                     @include('client.e_wallet')
                    </div>
                </div>
            </div>
            @endif
            @if($user->role=='writer')
                <div class="row"></div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Writer Performance
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
    <div class="modal fade" role="dialog" id="user_edit_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Edit User<a data-dismiss="modal" class="pull-right btn-danger btn">&times;</a></div>
                </div>
                <div class="modal-body">
                   <?php
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
                    ?>
                    <form class="form-horizontal ajax-post" method="post" action="{{ url("user/update/$user->id") }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-md-2">Name</label>
                            <div class="col-md-10">
                                <input class="form-control"  type="text" name="name" value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Email</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" name="email" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label class="col-md-2 control-label">Phone</label>
                            <div class="col-md-3">
                                <select class="form-control" name="country">

                                    @foreach($countryCallingCodes as $key=>$val)
                                        <?php
                                            $country = $user->country;
                                                                                    ?>
                                        <option {{ $user->country == $val.'(+'.$key.')' ? 'selected':''  }} value="{{ $val.'(+'.$key.')' }}">{{ $val.'(+'.$key.')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <input type="text" value="{{ $user->phone }}" class="form-control" name="phone">
                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">&nbsp;</label>
                            <div class="col-md-10">
                                <button class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection