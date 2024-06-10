                                <div aria-hidden="false" aria-expanded="true" role="tabpanel"
                                     class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-12"
                                     id="tab_services">
                                    <fieldset id="fieldset_services" class="orderform-step">
                                        <div class="table">
                                            <div id="box_academiclevel" class="row ui-buttonset" style="">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div class="mobile-tip" data-hasqtip="11"></div>
                                                    <label for="" class="left-label">Academic level:</label>
                                                </div>
                                                  <?php 
                                                        if(@$get_data['academic_id']){
                                                            $aca_id = @$get_data['academic_id'];
                                                        }else{
                                                          $aca_id = $academic_levels[0]->id;  
                                                        }
                                                            
                                                         ?>
                                                <div class="cell cell-right side_tip side_tip_academic_level">
                                                    <div class="selects visible-in-mobile" style="{{ count($academic_levels)>5 ? 'display:block !important;':'' }}">
                                                        <select onchange="setAcademicInput();" id="mob_academiclevel" name="academic_id" class="dr-down-input" style="">
                                                            @foreach($academic_levels as $academic_level)
                                <option {{ @$academic_level->id == $aca_id ? "selected":"" }} value="{{ $academic_level->id }}">{{ $academic_level->level }}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="radios visible-in-desktop" style="{{ count($academic_levels)>5 ? 'display:none !important;':'' }}">                                                      
                                                        @foreach($academic_levels as $academic_level)   
                                                        @if($academic_level->id == $aca_id)  
                                                         <input id="academic_input_{{ $academic_level->id }}" type="radio" title="{{ $academic_level->level }}" value="{{ $academic_level->id }}"
                                                               name="academiclevel" class="ui-helper-hidden-accessible"
                                                               checked="checked">
                                                        <label id="academic_label_{{ $academic_level->id }}" onclick="setAcademicId({{ $academic_level->id }});" for="academic_input_{{ $academic_level->id }}" data-hasqtip="0"
                                                               class="academic-class ui-button ui-widget ui-state-default ui-button-text-only ui-state-active"
                                                               role="button" aria-disabled="false" aria-pressed="true">
                                                            <span
                                                                    class="ui-button-text">{{ $academic_level->level }}</span>
                                                        </label>          
                                                        @else
                                                        <input id="academic_input_{{ $academic_level->id }}"  type="radio" title="{{ $academic_level->level }}" value="{{ $academic_level->id }}"
                                                               name="academiclevel" class="ui-helper-hidden-accessible">
                                                               <label id="academic_label_{{ $academic_level->id }}"  onclick="setAcademicId({{ $academic_level->id }});"
                                                                for="academic_input_{{ $academic_level->id }}"
                                                                class="academic-class ui-button ui-widget ui-state-default ui-button-text-only ui-corner-left"
                                                                role="button" aria-disabled="false" aria-pressed="false">
                                                            <span class="ui-button-text">{{ $academic_level->level }}</span>
                                                        </label>                                              
                                                                                                             
                                                                @endif
                                                        @endforeach
                                                          

                                                    </div>
                                                </div>
                                            </div>
                                            <div id="box_paper_type_id" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="15" class="mobile-tip"></div>
                                                    <label for="paper_type_id" class="left-label">Type of paper
                                                        needed:</label>
                                                </div>
                                                <div aria-describedby="qtip-14" data-hasqtip="14"
                                                     class="cell cell-right side_tip side_tip side_tip_type_of_paper">
                                                    <select
                                                            style=""
                                                            name="document_id" id="paper_type_id"
                                                            class="paper-type-id f_sz_400 validate[required] dr-down-input chosen-select"
                                                            data-finder="form.select.papertypeid">
                                                            @foreach($documents as $document)
                                    <option {{ @$get_data['document_id']==$document->id ? "selected":"" }} value="{{ $document->id }}">{{ $document->label }}</option>
                                @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div id="box_paper_type_id" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="15" class="mobile-tip"></div>
                                                    <label for="paper_size" class="left-label">Paper Size:</label>
                                                </div>
                                                <div aria-describedby="qtip-14" data-hasqtip="14"
                                                     class="cell cell-right side_tip side_tip side_tip_paper_size">
                                                    <select
                                                            style=""
                                                            name="paper_size" id="paper_size_id"
                                                            class="paper-type-id f_sz_400 validate[required] dr-down-input chosen-select"
                                                            data-finder="form.select.paper_size">
                                                <option>A4(British/Euaropean)</option>
                                                <option>US Letter Size</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="box_topcat_id" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="17" class="mobile-tip"></div>
                                                    <label for="" class="left-label">Subject or discipline:</label>
                                                </div>
                                                <div aria-describedby="qtip-16" data-hasqtip="16"
                                                     class="cell cell-right side_tip side_tip_discipline">
                                                    <select
                                                            style=""
                                                            name="subject_id" id="topcat_id"
                                                            class="topcat-id f_sz_400 validate[required] dr-down-input chosen-select">
                                                         @foreach($subjects as $subject)
                                                            <option {{ @$get_data['subject_id']==$subject->id ? "selected":"" }} value="{{ $subject->id }}">{{ $subject->label }}</option>
                                                         @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="box_toptitle" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="19" class="mobile-tip"></div>
                                                    <label for="" class="left-label">Topic:</label>
                                                </div>
                                                <div aria-describedby="qtip-18" data-hasqtip="18"
                                                     class="cell cell-right side_tip side_tip_toptitle">
                                                    <input value="Writer choice" name="topic" id="toptitle"
                                                           class="validate[required,custom[topiclength]] f_sz_full"
                                                           maxlength="1000" type="text">
                                                </div>
                                            </div>
                                            @if(@Auth::user()->role == 'admin')
                                            <div id="box_toptitle" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="19" class="mobile-tip"></div>
                                                    <label for="" class="left-label">Order Number:</label>
                                                </div>
                                                <div aria-describedby="qtip-18" data-hasqtip="18"
                                                     class="cell cell-right side_tip side_tip_toptitle">
                                                    <input onchange="" value="" name="order_number" id=""
                                                           class="validate[required,custom[topiclength]] f_sz_full"
                                                           maxlength="1000" type="text">
                                                </div>
                                            </div>
                                            @endif
                                            <div id="box_paperdets" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="21" class="mobile-tip"></div>
                                                    <label for="" class="left-label">Paper instructions:</label>
                                                </div>
                                                <div aria-describedby="qtip-20" data-hasqtip="20"
                                                     class="cell cell-right side_tip side_tip_paperdets">
                                                <textarea name="instructions" class="expand50-200 f_sz_full" rows="4"
                                                          id="instructions" placeholder="Optional field..."></textarea>
                                                </div>
                                            </div>
                                            <div id="box_min_source" class="row">
                                                <div class="cell cell-left">
                                                    <label for="" class="left-label">Sources:</label>
                                                </div>
                                                <div class="cell cell-right">
                                                    <div id="contentspinmin_source" class="contentspin clearfix">
                                                        <button onclick="minusSources();" type="button"
                                                                class="dec buttonspin ui-state-default"
                                                                data-finder="spinner.decrease.min_source"
                                                                title="Decrease">−
                                                        </button>
                                                        <input name="sources" id="min_source"
                                                               class="inc buttonspi"
                                                               maxlength="5" value="0" min="0"
                                                               data-finder="form.input.minsource" type="text">
                                                        <button onclick="addSources();" type="button" class="inc buttonspin"
                                                                data-finder="spinner.increase.min_source"
                                                                title="Increase">+
                                                        </button>
                                                    </div>
                                                    <div class="field_tip"></div>
                                                </div>
                                            </div>
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

                                            <div id="box_first_draft_deadline" class="row ui-buttonset">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <div data-hasqtip="27" class="mobile-tip"></div>
                                                    <label for="" class="left-label">Deadline:</label>
                                                </div>
                                                <div data-hasqtip="26" class="side_tip side_tip_deadlines cell cell-right">
                                                    <input type="text" name="deadline" class="form-control">
                                                </div>
                                            </div>
                                            <div id="box_addmaterials" class="row">
                                                <div class="cell cell-left with-mobile-tip">
                                                    <label for="" class="left-label">Additional materials:</label>
                                                </div>
                                                <div aria-describedby="qtip-22" data-hasqtip="22"
                                                     class="cell cell-right cell-additional-materials side_tip side_tip_additional_materials with-mobile-tip checkbox-wrapper">
                                                    <div data-hasqtip="23" class="mobile-tip"></div>
                                                    <div id="order-customer-files-container"
                                                         class="additional_materials_files_container"
                                                         data-finder="form.container.order-customer-files-container">
                                                        <div id="filesform">
                                                            <input type="file" class="form-control" name="files[]">
                                                        </div>
                                                        <a onclick="return addFiles();" href="#"><i class="fa fa-plus fa-lg"></i></a>
                                                    </div>
                                                    <script type="text/javascript">
                                                        function addFiles(){
                                                            $("#filesform").append('<br/><input type="file" class="form-control" name="files[]">');
                                                            return false;
                                                        }
                                                    </script>

                                                </div>
                                            </div>
                                            <div id="box_paperformat" class="row ui-buttonset">
                                                <div class="cell cell-left">
                                                    <label for="" class="left-label">Paper format or citation
                                                        style:</label>
                                                </div>
                                                <div class="cell cell-right">
                                                    <div style="display: block;" class="selects visible-in-mobile">
                                                        <select onchange="setStyleInput();" 
                                                                style=""
                                                                class="dr-down-input" name="style_id"
                                                                id="mob_paperformat">
                                                                @foreach($styles as $style)
                                    <option value="{{ $style->id }}">{{ $style->label }}</option>
                                @endforeach
                                                          
                                                        </select>
                                                    </div>
                                                    <div style="display:none;" class="radios visible-in-desktop">
                                                            <?php  $style_id = $styles[0]->id ?>

                                                            @foreach($styles as $style)

                                                            @if($style_id == $style->id)

                                                        <input id="style_input_{{ $style->id }}" checked="checked" class="ui-helper-hidden-accessible"
                                                               title="{{ $style->label }}" value="{{ $style->id }}"
                                                               name="paperformat"
                                                               type="radio"><label onclick="changeStyle({{ $style->id }});" aria-pressed="true"
                                                                                   aria-disabled="false" role="button"
                                                                                   class="style_button ui-button ui-widget ui-state-default ui-button-text-only ui-corner-left ui-state-active"
                                                                                   for="style_input_{{ $style->id }}"
                                                                                   id="style_label_{{ $style->id }}"><span
                                                                    class="ui-button-text">{{ $style->label }}</span></label>


                                                                    @else

                                                        <input class="ui-helper-hidden-accessible" title="{{ $style->label }}"
                                                               id="style_input_{{ $style->id }}" value="{{ $style->id }}" name="paperformat"
                                                               type="radio"><label onclick="changeStyle({{ $style->id }});" aria-pressed="false"
                                                                                   aria-disabled="false" role="button"
                                                                                   class="style_button ui-button ui-widget ui-state-default ui-button-text-only"
                                                                                   for="style_input_{{ $style->id }}" id="style_label_{{ $style->id }}"><span
                                                                    class="ui-button-text">{{ $style->label }}</span></label>
                                                                    @endif

                                                         @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="orderform-buttons f_submit_box f_submit_box_logged">
                                            <button type="submit"
                                               class="orderform-submit button highlight large">Create Order</button>
                                        </div>
                                    </fieldset>
                                </div>
