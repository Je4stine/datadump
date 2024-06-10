@extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
<!-- <iframe src='https://view.officeapps.live.com/op/embed.aspx?src={!! $url !!}' width='90%' height='623px' frameborder='0'>This is an embedded <a target='_blank' href='http://office.com'>Microsoft Office</a> document, powered by <a target='_blank' href='http://office.com/webapps'>Office Online</a>.</iframe> -->
 @if($order->payments()->sum('amount')<$order->amount && $order->status==4)
        <div class="alert alert-info" style="font-size:medium;">
            Kindly, go through the file preview below.
            If you are satisfied, then please Pay the remaining {{ "$".($order->amount-$order->payments()->sum('amount')) }} and approve the order so as to download the MS Word version.
            If you need any changes, then let us know by clicking on Revise. You are entitled to free revisions within 14 days after the completion of the paper.
            Thank you for ordering from us.
            <div class="row"></div>
            <a class="btn btn-success btn-sm" href="{{ URL::to('stud/pay/'.''.$order->id) }}"><i class="fa fa-paypal"></i> Pay Pending({{ @number_format($order->amount-$order->payments()->sum('amount'),2) }})</a>
            <a class="btn btn-danger btn-sm" href="{{ URL::to('stud/dispute/'.''.$order->id) }}"><i class="fa fa-thumbs-down"></i> Revise</a>
        </div>
        @elseif($order->status==4)
        <div class="alert alert-info" style="font-size:medium;">
            Kindly, go through the file preview below.
            If you are satisfied, please approve the order so as to download the MS Word version.
            If you need any changes, then let us know by clicking on Revise. You are entitled to free revisions within 14 days after the completion of the paper.
            Thank you for ordering from us.
            <div class="row"></div>
                                        <a class="btn btn-success btn-xs" href="{{ URL::to('stud/approve/'.''.$order->id) }}"><i class="fa fa-thumbs-up"></i> Approve</a>

            <a class="btn btn-danger btn-sm" href="{{ URL::to('stud/dispute/'.''.$order->id) }}"><i class="fa fa-thumbs-down"></i> Revise</a>
        </div>
    @endif
    {{ $url }}
<iframe src="https://docs.google.com/viewer?embedded=true&url={!! $url !!}" frameborder="no" style="width:100%;height:500px;"></iframe>
<style type="text/css">
	.ndfHFb-c4YZDc-Wrql6b{
		display: none !important;
	}
</style>
<!-- 
<iframe src="https://docs.google.com/viewerng/viewer?url=https://www.customwriting.us/orders/order/download/776/previewer"></iframe> -->

@endsection