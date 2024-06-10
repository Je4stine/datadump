<?php
$user = Auth::user();
?>
<div role="tabpanel" class="tab-pane" id="order_details">
    <h3>Order Details</h3>
    {{--{{ dd($order) }}--}}
    <div id="actions" style="">
        <a href="{{ URL::to("user/view/client".'/'.$order->user->id) }}" class="btn btn-info"><i class="fa fa-user"></i> View Client</a>
          </div>
    <?php
    $now = date('y-m-d H:i:s');
    $deadline = $order->deadline;
    $today = date_create($now);
    $end = date_create($deadline);
    $diff = date_diff($today,$end);
    if($today>$end){
        if($diff->d){
            $remaining = '<span style="color: red;"><i class="fa fa-calendar"></i> Late: '.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
        }else{
            $remaining = '<span style="color: red;"><i class="fa fa-calendar"></i> Late: '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
        }
    }else{

        if($diff->d){
            $remaining = '<span style="color: darkgreen;"><i class="fa fa-calendar"></i> '.$diff->d.' Day(s) '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
        }else{
            $remaining = '<span style="color: darkgreen;"><i class="fa fa-calendar"></i> '.$diff->h.' Hr(s) '.$diff->i.' Min(s)</span>';
        }
    }
    ?>
    <table align="centre" class="table table-bordered">
        <tr>
            <td colspan="4"><p style="font-size: large; font-weight: bold;">#{{ $order->id}}-{{ $order->topic }}</p></td>
        </tr>
        @if($order->is_manual == 0)
        <tr>
            <td class="titlecolumn">Type of Paper</td>
            <td>{{ $order->document->label }}</td>
            <td class="titlecolumn">English Style</td>
            <td>{{ $order->language->label }}</td>

        </tr>
        <tr>
            <td class="titlecolumn">Subject Area</td>
            <td>{{ $order->subject->label }}</td>
            <th class="titlecolumn">Writer Category</th>
            <td>{{ @$order->writerCategory->name }}</td>
        </tr>
        <tr>
            <td class="titlecolumn">Academic Level</td>
            <td>{{ ucwords($order->academic->level) }}</td>
            <td class="titlecolumn">Sources</td>
            <td>{{ $order->sources }}</td>


        </tr>
        <tr>
            <td class="titlecolumn">Number of Pages</td>
            <?php $multiply = $order->spacing==2 ? 1:2  ?>
            <td>{{ $order->pages.' page(s) / '.$multiply*275*$order->pages.' Words' }}</td>
            <td class="titlecolumn">Referencing Style</td>
            <td>{{ $order->style->label }}</td>
        </tr>
        <tr>
            <td class="titlecolumn">Spacing</td>
            <td><?php
                if($order->spacing==1){
                    echo "Single Spaced";
                }else{
                    echo "Double Spaced";
                }?></td>
            <th class="titlecolumn">Total</th>
            <td>
                @if($order->amount<1)
                    <strong style="color:green">Pending</strong><a href="#quote_modal" class="btn btn-success" data-toggle="modal">Submit Quote</a>
                @else
                    {{ $order->currency ? number_format($order->amount*$order->currency->usd_rate,2)." ".$order->currency->abbrev:'$'.number_format($order->amount,2) }}
                    <strong style="font-weight: bold;">
                        @if(!$order->paid)
                            <span style="color:red;"><i class="fa fa-times"></i> Unpaid</span>
                        @endif
                        @if($order->paid)
                            <span style="color:green;"><i class="fa fa-check"></i> Paid</span>
                            @if($order->payments()->sum('amount')<$order->amount)
                                <span style="color:red;"><i class="fa fa-times"></i> Pending({{ @number_format($order->amount-$order->payments()->sum('amount'),2) }})</span>
                            @endif
                        @endif
                    </strong>
                @endif

            </td>

        </tr>

        <tr>
            <td class="titlecolumn">WriterID</td>
            <td>
                @if($order->writer_id)
                @if(isset($writer))
                    <?php  $writer = \App\User::find($order->writer_id) ?>
                    {{ @$writer->id.'#'.@$wrier->name.'('.@$writer->email.')'  }}
                @else
                    N/A
                @endif
                    @endif
            </td>
        </tr>
        <tr>
            <td class="titlecolumn">Discount</td>
            <td>{!! $order->discounted.'%' !!}</td>

        </tr>
        <tr>
            <td class="titlecolumn">Deadline</td>
            <td>{!! $remaining !!}</td>
            <td class="titlecolumn">Status</td>
            <td>
                @if($order->status>=1 && $order->status != 4 && $order->status != 6)
                    Working
                @elseif($order->status==0)
                    On Hold
                @else
                    Closed
                @endif
            </td>
        </tr>
        <tr>
            <td class="titlecolumn">Paper Size</td>
            <td>{!! $order->paper_size !!}</td>
            <td class="titlecolumn">Additionals</td>
            <td>
                <?php
                $features = [];
                if($order->add_features){
                    $features = \App\AdditionalFeature::whereIn('id',json_decode($order->add_features))->get();
                }
                ?>
                <ul>
                    @foreach($features as $feature)
                        <li>{{ $feature->name }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endif
        @if($order->is_manual)
            <tr>
                <th class="titlecolumn">Order Number</th>
                <td>{{ $order->order_number }}</td>
                <th class="titlecolumn">Pages</th>
                <td>{{ $order->pages }}</td>
            </tr>
            <tr>
                <th class="titlecolumn">Deadliner</th>
                <td>{{ $order->deadline }}</td>
                <td class="titlecolumn">Status</td>
                <td>
                    @if(!$order->is_manual)
                        @if($order->status>=1 && $order->status != 4 && $order->status != 6)
                            Working
                        @elseif($order->status==0)
                            On Hold
                        @else
                            Closed
                        @endif
                    @else
                        @if($order->manual_status>=1 && $order->manual_status != 4 && $order->manual_status != 6)
                            Working
                        @elseif($order->status == 10)
                            Pending Assignment
                        @else
                            Closed
                        @endif
                    @endif
                </td>
            </tr>
        @endif
        @if(Auth::user()->isAllowedTo('edit_order_details'))
            <tr>
                <td colspan="4">
                    <a href="#add_pages_modal" data-toggle="modal" class="btn btn-success"><i class="fa fa-plus"></i>Pages</a>
                    <a href="#add_instructions_modal" data-toggle="modal" class="btn btn-success"><i class="fa fa-plus"></i>Instructions</a>
                    <a href="#add_sources_modal" data-toggle="modal" class="btn btn-success"><i class="fa fa-plus"></i>Sources</a>
                    <a href="#add_hours_modal" data-toggle="modal" class="btn btn-success"><i class="fa fa-calendar"></i> Extend Deadline</a>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="4" align="center"><strong>Order Instructions</strong></td>
        </tr>
        <tr>
            <td colspan="4">{!! nl2br($order->instructions) !!}</td>
        </tr>

    </table>
    <style type="text/css">
        .titlecolumn {
            background: whitesmoke;
            white-space: nowrap;
            text-align: right;
            font-weight: bold;
            width: 5%;
        }
    </style>
</div>
<div id="assign_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <a class="btn btn-danger pull-right" class="close" data-dismiss="modal">&times;</a>
                <h4 class="modal-title"><label>Force Assign</label></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/force_assign") }}">
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <input type="hidden" name="_method" value="PUT">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Writer</label>
                        <div class="col-md-5">
                            <input required type="text" name="writer_id" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount" class="control-label col-md-3">Total</label>
                        <div class="col-md-2">
                            <input type="text" value="" onchange="convertTotal();" name="amount" class="form-control">
                        </div>
                        <label for="cpp" class="control-label col-md-1">CPP</label>
                        <div class="col-md-2">
                            <input onchange="convertCpp();" type="text" value="" name="cpp" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Bonus</label>
                        <div class="col-md-5">
                            <input required type="text" name="bonus" value="0.00" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Deadline</label>
                        <div class="col-md-5">
                            <input required type="text" name="deadline" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-5">
                            <button class="btn btn-success"><i class="fa fa-check"></i> Assign Writer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function convertCpp(){
        var cpp = $("input[name='cpp']").val();
        var pages = '{{ $order->pages }}';
        cpp = parseFloat(cpp);
        var pages = parseFloat(pages);
        var total = cpp*pages;
        total = total.toFixed(2);
        $("input[name='cpp']").val(cpp.toFixed(2));
        $("input[name='amount']").val(total);
    }

    function convertTotal(){
        var total = $("input[name='amount']").val();
        var pages = '{{ $order->pages }}';
        pages = parseFloat(pages);
        total = parseFloat(total);
        var cpp = total/pages;
        cpp = cpp.toFixed(2);
        $("input[name='amount']").val(total.toFixed(2));
        $("input[name='cpp']").val(cpp);
    }
</script>
<script type="text/javascript">
    $(function() {
        var ms = $("input[name='writer_id']").magicSuggest({
            data: '{{ URL::to('order/force_assign') }}',
            valueField: 'id',
            method:'get',
            displayField: 'email',
            required:true,
            maxSelection:1
        });
    });
</script>
<div id="add_pages_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                {{ 'Add Pages' }}
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/add_pages/$order->id") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Pages</label>
                        <div class="col-md-4">
                            <input type="number" min="1" class="form-control" name="pages">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if(!$order->is_manual)
<div id="quote_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                {{ 'Submit Quote' }}
            </div>
            <div class="modal-body">
                <?php
                $order_repo = new \App\Repositories\OrderRepository();
                $amount = $order_repo->calculateCost($order);
                ?>
                <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("order/quote/$order->id") }}">
                    {{ csrf_field() }}
                    <?php
                    $currencies = \App\Currency::orderBy('id','asc')->get();
                    ?>
                    <div class="form-group">
                        <label class="control-label col-md-3">Amount(USD)</label>
                        <div class="col-md-6">
                            <input id="total_price" type="text" value="{{ $amount }}" class="form-control" name="amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Currency</label>
                        <div class="col-md-3">
                            <select id="currency_select" name="currency_id" class="form-control" onchange="changeCurrency();">
                                @foreach($currencies as $currency)
                                    <option <?php echo $currency->usd_rate==1 ? "selected":"" ?> value="<?php echo $currency->id ?>"><?php echo $currency->abbrev ?></option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" disabled style="font-size: large;" class="form-control" id="foreign_currency">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Partial Payment?</label>
                        <div class="col-md-6">
                            <input type="checkbox" name="partial" value="1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Submit Quote</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
<div id="add_instructions_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                Add More Instructions
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/add_instructions/$order->id") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Instructions</label>
                        <div class="col-md-4">
                        </div>
                    </div><div class="form-group">
                        <div class="col-md-10">
                            <textarea rows="10" name="instructions" class="form-control">{!! $order->instructions !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id="add_sources_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                Add More Sources
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/add_sources/$order->id") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Sources</label>
                        <div class="col-md-4">
                            <input type="number" min="1" class="form-control" name="sources">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="add_hours_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                Extent Deadline
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="{{ URL::to("order/add_hours/$order->id") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Extent By<small>(Hours)</small></label>
                        <div class="col-md-4">
                            <input type="number" min="1" class="form-control" name="hours">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="mark_order_paid" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="pull-right btn btn-danger" data-dismiss="modal">&times;</button>
                Pay Order
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajax-post" method="post" action="{{ URL::to("order/$order->id/markpaid") }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-3">Amount</label>
                        <div class="col-md-6">
                            <input required type="text" value="{{ round($order->amount,2) }}" class="form-control" name="amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Method</label>
                        <div class="col-md-6">
                            <select class="form-control" name="via">
                                <option value="paypal">Paypal</option>
                                <option value="invoice">Invoice</option>
                                <option value="manual">manual</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Reference</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="reference">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if(!$order->is_manual)
<script type="text/javascript">
    jQuery(document).ready(function(){
        changeCurrency();
    });
    function changeCurrency(){
        var selected = $("#currency_select").val();
        var original = $("#total_price").val();
        var currencies =  JSON.parse('<?php echo json_encode($currencies->toArray()) ?>');
        for(i=0;i<currencies.length;i++){
            var currency = currencies[i];
            if(currency.id==selected){
                var new_amt = parseFloat(original)*parseFloat(currency.usd_rate);
                $("#foreign_currency").val(new_amt.toFixed(2)+' '+currency.abbrev);
            }
        }
    }
</script>
    @endif