 @extends(@Auth::user()->role=='admin' ? 'layouts.gentella':'layouts.'.env('LAYOUT'))
@section('content')
@include('client.order_js')
<div class="inner col-md-9" style="">
<?php
    $get_data = Request::all();
    ?>
    <link href="{{ URL::to('uvo/css.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('uvo/gcommon_css.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::to('uvo/gcustom_css.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ URL::to('chosen/chosen.jquery.js') }}" type="text/javascript"></script>
    <script src="{{ url("dropzone/dropzone.js") }}"></script>
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
    <div class="uvo-iso">

    <div style="position: relative;" class="container">
        <div class="inner-content" style="width:100% !important;">
            <div class="mainInRgt cabinet">

                <article class="box-orderNow">
                    <div class="box-orderNowIn">
                        <h1 class="formtitle" data-finder="form.h1.title">Place an order. <span>It's fast, secure, and confidential.</span>
                        </h1>

                        <div id="potato-form-data-restored-notice" class="form-data-restored-notice"
                             style="display:none;"></div>
                        <form enctype="multipart/form-data" id="order_form" action="{{ URL::to('/stud/new') }}" class="orderform orderform-bordered dropzones f uvoform_potato pull-left col-md-10" method="POST">
                        {{ csrf_field() }}
                            <select style="display:none;" name="language_id" onchange="getOrderCost();" class="form-control">
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}">{{ $language->label }}</option>
                                @endforeach
                            </select>
                            <div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="uvoform_tabs">
                                <ul role="tablist"
                                    class="orderform-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                                    <li aria-selected="true" aria-labelledby="ui-id-12" aria-controls="tab_services"
                                        tabindex="0" role="tab" id="li_tab_services"
                                        class="uvoform_nav_tab ui-state-default ui-corner-top ui-tabs-selected ui-state-active ui-tabs-active">
                                        <a tabindex="-1" role="presentation"
                                           class="input-model large ui-tabs-anchor" onclick="return stepOne();" href="#tab_services"><span
                                                    class="step-number">
                                                1. </span>Paper Details</a>
                                    </li>

                                    <li aria-selected="false" aria-labelledby="ui-id-13" aria-controls="tab_price"
                                        tabindex="-1" role="tab" id="li_tab_price"
                                        class="uvoform_nav_tab ui-state-default ui-corner-top">
                                        <a id="ui-id-13" tabindex="-1" role="presentation"
                                           class="input-model large ui-tabs-anchor" onclick="return stepTwo();" href="#tab_price"
                                        ><span class="step-number">2. </span>Price
                                            Calculation</a>
                                    </li>

                                     <li aria-selected="false" aria-labelledby="ui-id-13" aria-controls="tab_price"
                                        tabindex="-1" role="tab" id="li_tab_payment"
                                        class="uvoform_nav_tab ui-state-default ui-corner-top">
                                        <a id="ui-id-13" tabindex="-1" onclick="return stepThree();" role="presentation"
                                           class="input-model large ui-tabs-anchor" href="#tab_price"
                                        ><span class="step-number">3. </span>Payment Info
                                           </a>
                                    </li>
                                </ul>
                                @include('client.tab_paper')
                                @include('client.tab_price')
                                @include('client.tab_payment')

                            </div>

                        </form>
                    </div>
            </article>
        </div>
    </div>
</div>
</div>
</div>
<div class="col-md-3 summary-side visible-in-desktop">
        <div class="card-header">
            <div class="summary-header">Order Summary

            </div>
        </div>

                <table class="table table-bordered table-condensed table-summary">
                    <tr>
                        <td colspan="2" class="academic_level_summary"></td>
                    </tr>
                    <tr class="pages-summary">
                        <td><span class="pages_count">3</span> Page(s) x $<span class="pages_cpp">--</span> </td>
                        <th>$<span class="pages_total">50</span></th>
                    </tr>
                    <tr class="slides-summary">
                        <td><span class="slides_count">3</span> Slide(s) x $<span class="slides_cpp">--</span> </td>
                        <th>$<span class="slides_total">50</span></th>
                    </tr>
                    <tr class="charts-summary">
                        <td><span class="charts_count">3</span> Charts(s) x $<span class="charts_cpp">--</span> </td>
                        <th>$<span class="charts_total">50</span></th>
                    </tr>
                    <tr class="writer-summary">
                        <td>Writer Category</td>
                        <th class="writer_inc"></th>
                    </tr>
                </table>
</div>
<style type="text/css">
    @media screen and (max-width: 580px){
        .visible-in-desktop {
            display: none !important;
        }
        .visible-in-mobile{
            display: block !important;
        }
    }
    .chosen-singl{
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif !important;
        font-size: large !important;
        height: 38px;
    }
    .summary-header{
        color: white;
        font-size: 25px;
    }
    .summary-side{
        adding: 25px 20px; */
        /* background-color: #f0fcff; */
    box-sizing: border-box;
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(0,0,100,.1), 0 0 5px rgba(0,0,200,.08);
        border: 1px solid rgba(0,0,100,.09);
        position: fixed;
        right: 5px;
        top: 130px;
        max-width: 250px;
        background-color: #7cb149;
        /* font-size: large; */
        color: white;
        font-weight: 400;
    }
</style>
@endsection

