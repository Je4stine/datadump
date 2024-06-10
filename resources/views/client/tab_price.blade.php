<div aria-hidden="true" aria-expanded="false" style="display: none;" role="tabpanel"
     class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-13"
     id="tab_price">
    <fieldset id="fieldset_s2" class="orderform-step table no-padding-bottom">
        <div id="box_pagesreq" class="row ui-buttonset">
            <div class="cell cell-left with-mobile-tip">
                <div data-hasqtip="25" class="mobile-tip"></div>
                <label for="" class="left-label">Pages:</label>
            </div>
            <div data-hasqtip="24" class="side_tip side_tip_pages cell cell-right">
                <div id="contentspinpagesreq" class="contentspin clearfix">
                    <button type="button" class="dec buttonspin" onclick="minusPages();"
                            title="Decrease">−
                    </button>
                    <input id="pagesreq" name="pages" maxlength="5" value="{{ isset($_GET['pages']) ? $_GET['pages']:1 }}"
                           class="f_sz_30 validate[required,funcCall[pagesOrSlidesOrChartsRequired]]"
                           minval="0" onchange="formatPages()" type="text">
                    <button onclick="addPages();" type="button" class="inc buttonspin"
                            title="Increase">+
                    </button>
                </div>
                <span class="words_total"><span
                            id="words_total_qty">550</span> words</span>

                <div class="spacing-inliner">
                    <div style="" class="selects visible-in-mobile">
                        <select onchange="changeSpacing();"
                                style=""
                                class="dr-down-input" name="spacing" id="mob_spacing">
                            <option value="2">Double spaced
                            </option>
                            <option value="1">Single spaced</option>
                        </select>

                        <input type="hidden" name="real_spacig" value="2">
                    </div>
                    <div style="" class="radios radios-spacing visible-in-desktop">
                        <input checked="checked" class="ui-helper-hidden-accessible"
                               name="spacing_radio" id="spacing_double" value="1"
                               type="radio"><label onclick="selectDouble();" aria-pressed="true"
                                                   aria-disabled="false" role="button"
                                                   class="spacing-cls
                                                    ui-button ui-widget ui-state-default ui-button-text-only ui-corner-left ui-state-active"
                                                   data-hasqtip="4"
                                                   id="spacing_double_btn"
                                                   for="spacing_double"
                        ><span
                                    class="ui-button-text">Double spaced</span></label>
                        <input class="ui-helper-hidden-accessible" name="spacing_radio"
                               id="spacing_single" value="1"
                               type="radio"><label onclick="selectSingle();" aria-pressed="false"
                                                   aria-disabled="false" role="button"
                                                   class="spacing-cls ui-button ui-widget ui-state-default ui-button-text-only ui-corner-right"
                                                   data-hasqtip="5"
                                                   id="spacing_single_btn"
                                                   for="spacing_single"
                        ><span
                                    class="ui-button-text">Single spaced</span></label>
                    </div>
                </div>
            </div>
        </div>
        <div id="box_pagesreq" class="row ui-buttonset">
            <div class="cell cell-left with-mobile-tip">
                <div data-hasqtip="25" class="mobile-tip"></div>
                <label for="" class="left-label">Power Point Slides:</label>
            </div>
            <div data-hasqtip="24" class="side_tip side_tip_pages cell cell-right">
                <div id="contentspinpagesreq" class="contentspin clearfix">
                    <button type="button" class="dec buttonspin" onclick="minusSlides();"
                            title="Decrease">−
                    </button>
                    <input id="pagesreq" name="slides" maxlength="5" value="0"
                           class="f_sz_30 validate[required,funcCall[pagesOrSlidesOrChartsRequired]]"
                           minval="0" onchange="" type="number">
                    <button onclick="addSlides();" type="button" class="inc buttonspin"
                            title="Increase">+
                    </button>
                </div>
            </div>
        </div>
        <div id="box_pagesreq" class="row ui-buttonset">
            <div class="cell cell-left with-mobile-tip">
                <div data-hasqtip="25" class="mobile-tip"></div>
                <label for="" class="left-label">Charts:</label>
            </div>
            <div data-hasqtip="24" class="side_tip side_tip_pages cell cell-right">
                <div id="contentspinpagesreq" class="contentspin clearfix">
                    <button type="button" class="dec buttonspin" onclick="minusCharts();"
                            title="Decrease">−
                    </button>
                    <input id="pagesreq" name="charts" maxlength="5" value="0"
                           class="f_sz_30 validate[required,funcCall[pagesOrSlidesOrChartsRequired]]"
                           minval="0" onchange="formatPages()" type="number">
                    <button onclick="addCharts();" type="button" class="inc buttonspin"
                            title="Increase">+
                    </button>
                </div>
            </div>
        </div>
        <div id="box_first_draft_deadline" class="row ui-buttonset">
            <div class="cell cell-left with-mobile-tip">
                <div data-hasqtip="27" class="mobile-tip"></div>
                <label for="" class="left-label">Deadline:</label>
            </div>
            <div data-hasqtip="26" class="side_tip side_tip_deadlines cell cell-right">
                <div style="" class="selects visible-in-mobile">
                    <select onchange="setSelectedRate();"
                            style=""
                            class="dr-down-input"  name="rate_id" id="mob_deadline">
                    </select>
                </div>
                <div style="" class="radios visible-in-desktop" id="deadlines">

                </div>
            </div>
        </div>
        <div id="box_writer_preferences" class="row">
            <div class="cell cell-left with-mobile-tip">
                <div data-hasqtip="29" class="mobile-tip"></div>
                <label for="" class="left-label">Category of writer:</label>
            </div>
            <div data-hasqtip="28"
                 class="side_tip side_tip_category_of_writer cell cell-right">
                <div style="" class="selects visible-in-mobile">
                    <select onchange="changeWriterCategory();"
                            style=""
                            class="dr-down-input" name="writer_category_id"
                            id="mob_writer_preferences">
                        @foreach($writer_categories as $writer_category)
                            <option value="{{ $writer_category->id }}">{{ $writer_category->name }}
                                @if($writer_category->amount>0)
                                    +{{ $writer_category->inc_type!='percent' ? "$":"" }}{{ $writer_category->amount }}{{ $writer_category->inc_type=='percent' ? "%":"" }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style=""
                     class="radios writer-category ui-buttonset visible-in-desktop"
                     id="box_category_of_writer_rad">
                    <?php $no=0; ?>
                    @foreach($writer_categories as $writer_category)
                        <input {{ $no ==0 ? 'checked="checked"':''}}  class="ui-helper-hidden-accessible"
                               id="radio_writer_preferences_{{ $writer_category->id }}" name="writer_preferences"
                               value="{{ $writer_category->id }}"
                               type="radio"><label onclick="setWriterCategory({{ $writer_category->id }});" aria-pressed="true" aria-disabled="false"
                                                   role="button"
                                                   class="ui-button writer-cat-class ui-widget ui-state-default ui-button-text-only ui-corner-left {{ $no ==0 ? 'ui-state-active':''}}"
                                                   for="radio_writer_preferences_{{ $writer_category->id }}"
                                                   id="tip_radio_writer_preferences_{{ $writer_category->id }}"
                        ><span
                                    class="ui-button-text">{{ $writer_category->name }}<span
                                        class="writer_preferences_text">{{ $writer_category->description }}
                                    @if($writer_category->amount>0)
                                        <strong>+{{ $writer_category->inc_type!='percent' ? "$":"" }}{{ $writer_category->amount }}{{ $writer_category->inc_type=='percent' ? "%":"" }}</strong>
                                    @endif
                                </span></span></label>
                        <?php $no++; ?>
                    @endforeach


                </div>
            </div>

    </fieldset>
    <div class="features-block">
        <h4 class="step_header features-header">Additional features</h4>
        <p><strong>Note:</strong> Progressive Delivery is not available for pages less than 10 or Partial payments</p>
        <div id="yes_no_features" class="features">

            @foreach($additional_features as $feature)
                <?php
                similar_text(strtolower($feature->name),'progressive delivery',$percent);
                ?>
                @if($percent>80)
                    <input class="progressive_input" disabled value="{{ $feature->id }}" name="feature_ids[]" id="feature_{{ $feature->id }}"
                           type="checkbox">
                    <label onclick="changeFeatured({{ $feature->id }})" data-hasqtip="8" class="feature large" for="feature_id_{{ $feature->id }}"
                           id="tip_order_samples_{{ $feature->id }}">
                        <div class="feature-header">
                            {{ $feature->name }}
                        </div>
                        <span class="feature-price">+{{ $feature->inc_type == 'money' ? "$".$feature->amount:$feature->amount."%" }}</span>
                    </label>
                @else
                    <input value="{{ $feature->id }}" name="feature_ids[]" id="feature_{{ $feature->id }}"
                           type="checkbox">
                    <label onclick="changeFeatured({{ $feature->id }})" data-hasqtip="8" class="feature large" for="feature_id_{{ $feature->id }}"
                           id="tip_order_samples_{{ $feature->id }}">
                        <div class="feature-header">
                            {{ $feature->name }}
                        </div>
                        <span class="feature-price">+{{ $feature->inc_type == 'money' ? "$".$feature->amount:$feature->amount."%" }}</span>
                    </label>
                    @endif

                    @endforeach

                    </label>
        </div>
        <!-- /yes_no_features -->
    </div>
    <div class="step2_total_price">
        <div id="box_total_price" class="step2_total_price_blocks">
            <div style="width: 60% !important;" class="step2_total_price_block step2_total_price_text">
                <p>The discount information is available on the next step of placing the
                    order.
                </p>
            </div>
            <div class="step2_total_price_block step2_total_price_place">
                <p>
                    @if(!@$get_data['partial'])
                        Grand total price:
                        <select style="max-width: 80px;" class="form-control" name="currency_id" id="currency_select" onchange="changeCurrency();">
                            <?php foreach($currencies as $currency): ?>
                            <option {{ $currency->usd_rate==1 ? "selected":"" }} value="<?php echo $currency->id ?>"><?php echo $currency->abbrev ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id=""
                              class="total-price-sum total_price total_price_box total_price_box_full"></span>
                    @else
                        Deposit Amount:
                        <select style="max-width: 80px;" class="form-control" name="currency_id" id="currency_select" onchange="changeCurrency();">
                            <?php foreach($currencies as $currency): ?>
                            <option {{ $currency->usd_rate==1 ? "selected":"" }} value="<?php echo $currency->id ?>"><?php echo $currency->abbrev ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span id=""
                              class="deposit_amt total-price-sum total_price total_price_box"></span>
                    @endif
                </p>
            </div>
        </div>
        <div class="orderform-buttons f_submit_box f_submit_box_logged">
            <a onclick="return stepThree();" href="javascript:void(0)"
               class="orderform-submit button highlight large to_step3_btn"
               data-finder="form.link.tostep3">Go to step 3. Personal information →</a>
            <a onclick="return stepOne();" href="#"
               class="orderform-prev input-model large order_form_repeat_step1"
               data-finder="form.link.backtostep1">← Previous step</a>
        </div>
    </div>
</div>
