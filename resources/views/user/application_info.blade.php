 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">Application info for <strong>{{ $user->name }}</strong> @if(Auth::user()->isAllowedTo('activate_writer') && $user->status==0) <a onclick="return confirm('Are you sure?');" href="{{ URL::to("user/$user->id/activate") }}" class="btn btn-info pull-right">Activate</a> @endif</div>
        <div class="panel-body">
           <div class="col-md-5">
               <div class="card section">
                   <div class="card-header">
                       Application Info
                   </div>
                   <div class="panel-body">
                       <table class="table table-bordered table-condensed">
                           @if(!@$profile->step)
                               <tr>
                                   <td colspan="2">
                                       <div class="alert alert-info">Profile Form not yet completed</div>
                                   </td>
                               </tr>
                               @else

                           </tr>
                               <tr>
                               <th>Native Language</th>
                               <td>{{ @$profile->payment_terms }}</td>
                           </tr>
                           <tr>
                               <th>Education Level</th>
                               <td>{{ @$profile->academic->level }}</td>
                           </tr>
                           <tr>
                               <th>Paypal Email</th>
                               <td>{{ @$profile->payment_terms }}</td>
                           </tr>
                           <tr>
                               <th>Was Writer for</th>
                               <td>{{ @$profile->other_company,'N/A' }}</td>
                           </tr>
                           <tr>
                               <th colspan="2" align="">About</th>
                           </tr>
                           <tr>
                           </tr>
                           <tr>
                               <th>Subjects</th>
                               <td>
                                   <ul>
                                       @foreach($subjects as $subject)
                                           <li>{{ $subject->label }}</li>
                                       @endforeach
                                   </ul>

                               </td>
                           </tr>
                           <tr>
                               <th>Writing Styles</th>
                               <td>
                                   <ul>
                                       @foreach($styles as $style)
                                           <li>{{ $style->label }}</li>
                                       @endforeach
                                   </ul>

                               </td>
                           </tr>
                           @if($user->writerTest)
                               <tr>
                                   <th>Essay Test</th>
                                   <td>
                                      Topic: {{ $user->writerTest->randomTest->topic }}
                                       {{ @$profile->writerTest->file_id }}<a class="btn btn-sm btn-success pull-right" href="{{ URL::to("order/download/".$user->writerTest->file_id) }}"><i class="fa fa-download"></i> </a>
                                   </td>
                               </tr>
                               @endif
                           <tr>
                               <th>Certificate</th>
                               <td>
                                   @if(isset($profile->cert_title))
                                   {{ @$profile->cert_title }}<a class="btn btn-sm btn-success pull-right" href="{{ URL::to("order/download/$profile->cert_file_id") }}"><i class="fa fa-download"></i> </a>
                              @endif
                               </td>
                           </tr>
                           <tr>
                               <th colspan="2" align="center">Sample Essays</th>
                           </tr>
                           <tr>
                               <td colspan="2">
                                   <ul>
                                       @if(@$profile->sample_essays)
                                       @foreach(json_decode($profile->sample_essays) as $essay)
                                           <li>{{ $essay->title }}<a class="btn btn-xs btn-success pull-right" href="{{ URL::to("order/download/$essay->file_id") }}"><i class="fa fa-download"></i> </a></li>
                                       @endforeach
                                           @endif
                                   </ul>

                               </td>
                           </tr>
                               @endif
                       </table>
                   </div>
               </div>
           </div>
            <div class="col-md-4">
                <div class="card section">
                    <div class="card-header">Personal Details</div>
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
                            <tr>
                                <th>E-mail</th>
                                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ ucwords($user->role) }}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{ ucwords($user->country) }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ ucwords($user->phone) }}</td>
                            </tr>
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection