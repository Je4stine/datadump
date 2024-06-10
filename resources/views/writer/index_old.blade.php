 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
    <div class="card section">
        <div class="card-header">
            <div class="panel-title">Writer Dashboard</div>
        </div>
        <div class="panel-body">
            <?php
                    $user = Auth::user();
            $total =  $user->assigns()->where('status','>',2)->whereMonth('updated_at','=',date('m'))->whereYear('updated_at', '=', date('Y'))->sum('amount');
                $bonus = $user->assigns()->where([
                        'status'=>4
                ])->whereMonth('updated_at','=',date('m'))->whereYear('updated_at', '=', date('Y'))->sum('bonus');
            $fines =  $user->assigns()->where('status','>',2)->whereMonth('updated_at','=',date('m'))->whereYear('updated_at', '=', date('Y'))->sum('fine');

            ?>
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">Order Statistics</div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Available</th>
                                <td>
                                    {{ $available  }}
                                </td>
                                <th>Bidded</th>
                                <td>
                                    {{ $bidded }}
                                </td>
                            </tr>
                            <tr>
                                <th>Active</th>
                                <td>
                                    {{ $active }}
                                </td>
                                <th>Revision</th>
                                <td>
                                  {{ $revision }}
                                </td>
                            </tr>
                            <tr>
                                <th>Completed</th>
                                <td>
                                    {{ $completed }}
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card section">
                    <div class="card-header">{{ date('M, Y').' Payment Statistics' }}</div>
                    <div class="panel-body">
                       <table class="table table">

                           <tr>
                               <th>Total</th>
                               <td>
                                   {{
                                   number_format($total,2)
                                   }}
                               </td>
                           </tr>
                           <tr>
                               <th>Bonus</th>
                               <td>
                                   {{
                                   number_format($bonus,2)
                                   }}
                               </td>
                           </tr>
                           <tr>
                               <th>Fine</th>
                               <td>
                                   {{
                                       number_format($fines,2)
                                   }}
                               </td>
                           </tr>
                       </table>
                    </div>
                </div>
            </div>
            <div class="row"></div>
            <div class="col-md-12">
                <div class="card section">
                    <div class="card-header">Announcements</div>
                    <div class="panel-body">
                        <?php
                            $announcements = \App\Announcement::where('target','LIKE','writers')->orderBy('id','desc')->paginate(5);
                            ?>
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                            @foreach($announcements as $announcement)
                                <tr>
                                    <td>{{ $announcement->id }}</td>
                                    <td><?php echo  str_replace('{name}','<strong>'.Auth::user()->name.'</strong>' ,nl2br($announcement->message)) ?></td>
                                    <td>{{ date('d/m/Y H:i', strtotime($announcement->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </table>
                        {{ $announcements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection