<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Order Management System</title>
    <script src="{{ URL::to('js/jquery.min.js') }}"></script>

    <script src="{{ URL::to('magicsuggest/magicsuggest.js') }}"></script>
    <script src="{{ URL::to('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('js/jquery.datetimepicker.js') }}"></script>

    <script src="{{ URL::to('rating/jquery.MetaData.js') }}"></script>
    <link href="{{ URL::to('rating/jquery.rating.css') }}" rel="stylesheet">
    <script src="{{ URL::to('rating/jquery.rating.js') }}"></script>



    <link rel="stylesheet" href="{{ URL::to('css/star-rating.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('css/chat.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ URL::to('css/throbber.css') }}" media="all" rel="stylesheet" type="text/css"/>

    <script src="{{ URL::to('js/star-rating.js') }}" type="text/javascript"></script>
    <script src="{{ URL::to('js/jquery.toaster.js') }}"></script>
    <script src="{{ URL::to('js/local.js') }}"></script>

    <!-- Bootstrap Core CSS -->
    <link href="{{ URL::to('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core CSS -->

    <!-- Custom CSS -->
    <link href="{{ URL::to('css/sb-admin.css') }}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{ URL::to('css/plugins/morris.css') }}" rel="stylesheet">
    <!-- Custom Fonts -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to($navbar_menu->url)  }}">{{ $navbar_menu->label }}</a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            @if($navbar_menu->account)
                @foreach($navbar_menu->account as $rmenu)
                    @if(count($rmenu->children)>0)
                        <li class="dropdown user-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?php
                                $label = str_replace("{name}",Auth::user()->name,$rmenu->label);
                                if($rmenu->label=="{email}"){
                                    $label =  str_replace("{email}",Auth::user()->email,$rmenu->label);
                                }
                                ?>
                                {{ $label }} <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                @foreach($rmenu->children as $child)
                                    <li><a href="{{ URL::to($child->url) }}"><i class="fa {{ $child->icon }}"></i> {{ $child->label }}</a></li>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                    @endif
                @endforeach
            @endif
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="sidebar-search" style="padding: 15px;">
                    <form id="searchbox">
                        {{ csrf_field() }}
                        <div class="input-group custom-search-form">
                            <input type="text" name="search" id="searchbox" class="form-control" placeholder="Order id or topic...">
                                <span class="input-group-btn">
                                <button onclick="return searchOrders()" class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </li>
                @foreach($menus as $menu)
                    @if($menu->type=='single')
                        <li>
                            <a href="{{ URL::to($menu->menus->url)  }}"><i class="fa {{ $menu->menus->icon }}"></i> {{ $menu->menus->label }}</a>
                        </li>
                    @endif
                    @if($menu->type=='many')
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa {{ $menu->icon }}"></i>    {{ $menu->label }} <i class="fa fa-caret-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($menu->menus as $drop)
                                    <li><a href="{{ URL::to($drop->url) }}"><i class="fa {{ $drop->icon }}"></i> {{ $drop->label }}</a><li>
                                @endforeach
                            </ul>
                            <!-- /.dropdown-user -->
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">
        <div style="min-height: 600px;" class="container-fluid">
        <br/>
        <div class="col-md-12">
            @yield('content')
        </div>
        <!-- /.container-fluid -->
</div>
    </div>
    <!-- /#page-wrapper -->

</div>
</body>
<script src="{{ URL::to('js/customjs.js') }}"></script>
</html>
@if(session('notice'))
    <script type="text/javascript">
        $.toaster({ priority : "{{ session('notice')['class'] }}", title : "{{ session('notice')['class'] }}", message : "{{ session('notice')['message'] }}"});
    </script>
    <?php session()->forget('notice'); ?>
@endif
<script type="text/javascript">


    function searchOrders(){
        var data = $("#searchbox").serialize();
        $("#searchresults").modal('show');
        $.post('{{ URL::to(Auth::user()->role."/search") }}',data,function(response){
            var orders = JSON.parse(response);
            $("#lod").hide();
            $('#searchtable tr:not(:first)').remove();
            for(var i=0;i<orders.length;i++){
                var order = orders[i];
                var status = 'new';
                if(order.status==4){
                    status = 'Completed';
                }
                if(order.status==3){
                    status = 'Pending';
                }
                if(order.status==2){
                    status = 'Revision';
                }
                if(order.status==1){
                    status = 'New';
                }
                if('{{ Auth::user()->role }}'=='admin'){
                    var url = '{{ URL::to('order') }}/'+order.id;
                }else{
                    var url = '{{ URL::to('writer/order') }}/'+order.id;
                }
                $("#searchtable").append('<tr>' +
                        '<td>'+order.id+'</td>'+
                        '<td>'+order.topic+'</td>' +
                        '<td>'+order.subject+'</td>' +
                        '<td>'+order.pages+'</td>' +
                        '<td>'+status+'</td>' +
                        '<td><a target="_blank" href="'+url+'" style="font-size: larger;" class="label label-info"><i class="fa fa-eye"></i>View</a></td>' +
                        '</tr>')
            }
        });
        return false;
    }
</script>
<div id="searchresults" class="modal fade" role="dialog">
    <div style="width:80%"  class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn btn-primary pull-right" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><label>Search Results &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div style="" id="lod" class="throbber-loader">
                            Loading…
                        </div></label></h4>
            </div>
            <div class="modal-body">
                <table id="searchtable" class="table table-bordered">
                    <tr>
                        <th>Order#</th>
                        <th>Topic</th>
                        <th>Subject</th>
                        <th>Pages</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </table>
            </div>
        </div>

    </div>
</div>
