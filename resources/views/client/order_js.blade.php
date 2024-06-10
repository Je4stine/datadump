<?php
if(@$get_data['academic_id']){
$aca_id = @$get_data['academic_id'];
}else{
$aca_id = $academic_levels[0]->id;
}

?>
<script type="text/javascript">

var featured = [];
    var settings = JSON.parse('<?php echo  str_replace("'","",json_encode($settings)) ?>');
    var checked = jQuery("input[name='academiclevel']:checked").val();
    var rate = null;
    var initial_rate = '{{ @$get_data['rate_id'] }}';
    var id = jQuery("select[name='academic_id']").selected();
    $(document).ready(function(){
        var initial_rate = '{{ @$get_data['rate_id'] }}';
        var id = jQuery("select[name='academic_id']").val();
        setAcademicId('{{ $aca_id }}');
    });
    jQuery(".chosen-select").chosen({disable_search_threshold: 10});
    jQuery(".chosen-container").width('100%');
    function setAcademicId(id){
        jQuery("select[name='academic_id']").val(id);
        jQuery("#academic_input_"+id).attr('checked','checked');
        jQuery(".academic-class").removeClass('ui-state-active')
        jQuery(".academic-class").attr('aria-pressed','false');
        jQuery("#academic_label_"+id).addClass('ui-state-active');
        jQuery("#academic_label_"+id).attr('aria-pressed','true');
        setAcademic(id);
    }

    function setAcademicInput(){
        var id = jQuery("select[name='academic_id']").val();
        setAcademicId(id);
    }

    function setStyleInput(){
        var id = jQuery("select[name='style_id']").val();
        changeStyle(id);
    }

    function changeStyle(id){
        jQuery("select[name='style_id']").val(id);
        jQuery("#style_input_"+id).attr('checked','checked');
        jQuery(".style_button").removeClass('ui-state-active')
        jQuery("#style_label_"+id).addClass('ui-state-active');
        jQuery("#style_label_"+id).attr('aria-pressed','true');
    }

    function addPages(){
        var pages = jQuery("input[name='pages']").val();
        pages =  parseInt(pages) || 0;
        pages++;
        jQuery("input[name='pages']").val(pages);
        countWords();
    }

    function minusPages(){
        var pages = jQuery("input[name='pages']").val();
        pages =  parseInt(pages) || 1;
        if(pages>0){
            pages--;
        }
        var words = 275*pages;
        jQuery("input[name='pages']").val(pages);
        countWords();
    }function addSlides(){
        var pages = jQuery("input[name='slides']").val();
        pages =  parseInt(pages) || 0;
        pages++;
        jQuery("input[name='slides']").val(pages);
        countWords();
    }

    function minusSlides(){
        var pages = jQuery("input[name='slides']").val();
        pages =  parseInt(pages) || 1;
        if(pages>0){
            pages--;
        }
        jQuery("input[name='slides']").val(pages);
        countWords();
    }function addCharts(){
        var pages = jQuery("input[name='charts']").val();
        pages =  parseInt(pages) || 0;
        pages++;
        jQuery("input[name='charts']").val(pages);
        countWords();
    }

    function minusCharts(){
        var pages = jQuery("input[name='charts']").val();
        pages =  parseInt(pages) || 1;
        if(pages>0){
            pages--;
        }
        jQuery("input[name='charts']").val(pages);
        countWords();
    }
    function addSources(){
        var sources = jQuery("input[name='sources']").val();
        sources =  parseInt(sources) || 1;
        sources++;
        jQuery("input[name='sources']").val(sources);
    }

    function formatPages(){
        var pages = jQuery("input[name='pages']").val();
        pages =  parseInt(pages) || 1;
        var words = 275*pages;
        jQuery("#words_total_qty").html(words);
        jQuery("input[name='pages']").val(pages);
        countWords();
    }

    function minusSources(){
        var sources = jQuery("input[name='sources']").val();
        sources =  parseInt(sources) || 0;
        if(sources>0){
            sources--;
        }
        jQuery("input[name='sources']").val(sources);
    }

    function stepOne(){
        jQuery(".uvoform_nav_tab").removeClass('ui-state-active');
        jQuery(".uvoform_nav_tab").removeClass('ui-tab-active');
        jQuery("#li_tab_services").addClass('ui-tab-active');
        jQuery("#li_tab_services").addClass('ui-state-active');
        jQuery("#tab_price").slideUp();
        jQuery("#tab_services").slideDown();
        jQuery("#tab_personal").slideUp();
        return false;
    }
    function stepTwo(){
        jQuery(".uvoform_nav_tab").removeClass('ui-state-active');
        jQuery(".uvoform_nav_tab").removeClass('ui-tab-active');
        jQuery("#li_tab_price").addClass('ui-tab-active');
        jQuery("#li_tab_price").addClass('ui-state-active');
        jQuery("#tab_services").slideUp();
        jQuery("#tab_personal").slideUp();
        jQuery("#tab_price").slideDown();
        return false;
    }

    function stepThree(){
        jQuery(".uvoform_nav_tab").removeClass('ui-state-active');
        jQuery(".uvoform_nav_tab").removeClass('ui-tab-active');
        jQuery("#li_tab_payment").addClass('ui-tab-active');
        jQuery("#li_tab_payment").addClass('ui-state-active');
        jQuery("#tab_price").slideUp();
        jQuery("#tab_services").slideUp();
        jQuery("#tab_personal").slideDown();
        return false;
    }

    function setAcademic(id){
        jQuery("select[name='rate_id']").html('');
        jQuery("#deadlines").html('');
        var academics = this.settings.academics;
        var rates = this.settings.rates;
//		console.log(rates);
        var selected = 0;
        for(var i =0;i<rates.length;i++){
            var rate = rates[i];
            if(rate.academic_id==id && rate.deleted==0){
                jQuery("select[name='rate_id']").append('<option value="'+rate.id+'">'+rate.label+'</option>');
                if(selected==0){
                    selected = 1;
                    jQuery("#deadlines").append(getCheckedRateString(rate));
                }else{
                    jQuery("#deadlines").append(getRateString(rate));
                }
            }
        }
        for(var l=0;l<academics.length;l++){
            if(academics[l].id == id){
                jQuery(".academic_level_summary").html(academics[l].level);
            }
        }
        getOrderCost();
    }

    function setPaymentMethod(id){
        jQuery("select[name='payment_method']").val(id);
        jQuery("#payment_method_"+id).attr('checked','checked');
        jQuery(".payment_lbl").removeClass('ui-state-active');
        jQuery("#payment_label_"+id).addClass('ui-state-active');
    }

    function getCheckedRateString(rate){
        var str = '<input checked="checked" class="ui-helper-hidden-accessible deadline_radio" id="radio_deadline_'+rate.id+'"'+
                'name="deadline" value="'+rate.id+'"  type="radio"><label'+
                'aria-pressed="true" onclick="changeRate('+rate.id+')" aria-disabled="false" role="button"'+
                'class="ui-button ui-state-active ui-widget deadline_label ui-state-default ui-button-text-only" for="radio_deadline_'+rate.id+'"'+
                'id="tip_radio_deadline_'+rate.id+'"><span'+
                'class="ui-button-text">'+rate.label+'</span></label>';
        return str;
    }

    function getRateString(rate){
        var str = '<input class="ui-helper-hidden-accessible deadline_radio" id="radio_deadline_'+rate.id+'"'+
                'name="deadline" value="'+rate.id+'"  type="radio"><label'+
                'aria-pressed="false" onclick="changeRate('+rate.id+')" aria-disabled="false" role="button"'+
                'class="ui-button ui-widget ui-state-default deadline_label ui-button-text-only" for="radio_deadline_'+rate.id+'"'+
                'id="tip_radio_deadline_'+rate.id+'"><span'+
                'class="ui-button-text">'+rate.label+'</span></label>';
        return str;
    }

    function changeRate(id){
        jQuery("select[name='rate_id']").val(id);
        jQuery("#radio_deadline_"+id).attr('checked','checked');
        jQuery(".deadline_label").removeClass('ui-state-active');
        jQuery("#tip_radio_deadline_"+id).addClass('ui-state-active');
        getOrderCost();
    }

    function setSelectedRate(){
        var id = jQuery("select[name='rate_id']").val();
        changeRate(id);
    }

    function selectSingle(){
        jQuery(".spacing-cls").removeClass('ui-state-active');
        jQuery("#spacing_single_btn").addClass('ui-state-active');
        jQuery("select[name='spacing']").val(1);
        jQuery("input[name='real_spacig']").val(1);
        countWords();
    }

    function selectDouble(){
        jQuery(".spacing-cls").removeClass('ui-state-active');
        jQuery("#spacing_double_btn").addClass('ui-state-active');
        jQuery("select[name='spacing']").val(2);
        jQuery("input[name='real_spacig']").val(2);
        countWords();
    }

    function changeSpacing(){
        var spacing = jQuery("select[name='spacing']").val();
        if(spacing==2){
            selectDouble();
        }else{
            selectSingle();
        }
        countWords();
    }

    function countWords(){
        var spacing = jQuery("select[name='spacing']").val();
        var pages = jQuery("input[name='pages']").val();
        var partial = '{{ @$_GET['partial'] }}';
        if(partial <1 && pages>=10){
            jQuery(".progressive_input").removeAttr('disabled');
            console.log('enabled progress');
        }
        if(partial > 0 || pages<10){
            var id = jQuery(".progressive_input").val();
            if(this.featured != undefined){
                var index = this.featured.indexOf(id);
                this.featured.splice(index,1);
                jQuery('#feature_'+id).prop('checked', false);
                jQuery(".progressive_input").attr('disabled',true);
            }

        }


        pages = parseInt(pages)||1;
        spacing = parseInt(spacing)||2;
        var multi = 1;
        if(spacing==1){
            multi = 2;
        }
        var words = 275*multi*pages;
        jQuery("#words_total_qty").html(words);
        getOrderCost();
    }

    function setWriterCategory(id){
        jQuery(".writer-cat-class ").removeClass('ui-state-active');
        jQuery("#tip_radio_writer_preferences_"+id).addClass('ui-state-active');
        jQuery("select[name='writer_category_id']").val(id);
        getOrderCost();
    }

    function changeWriterCategory(){
        var cat_id = jQuery("select[name='writer_category_id']").val();
        setWriterCategory(cat_id);
    }

    jQuery(document).ready(function(){
        var id = jQuery("select[name='academic_id']").val();
        setAcademicId(id);
        formatPages();
        jQuery(".chosen-select").chosen({disable_search_threshold: 10});
        jQuery(".chosen-container").width('100%');
    });



    function getRate(){
        var rate_id =  jQuery("select[name='rate_id']").val();
        var rates = this.settings.rates;
        var found = null;
        for(var i=0;i<rates.length;i++){
            var rate = rates[i];
            if(rate.id==rate_id){
                found = rate;
                break;
            }
        }
//            console.log(found);
        return found;
    }
    
    function changeFeatured(id){
        var checked = jQuery('#feature_'+id).is(':checked');
        if(checked==true){
            var index = this.featured.indexOf(id);
            this.featured.splice(index,1);
            jQuery('#feature_'+id).prop('checked', false);
        }else{
            jQuery('#feature_'+id).prop('checked', true);
            this.featured.push(id);
        }
        getOrderCost();
    }

    function getFeature(id){
        var additional_features = this.settings.additional_features;
        var featured = this.featured;
        var amt = 0;
        for(var i=0;i<additional_features.length;i++){
            var feature = additional_features[i];
            if(featured.indexOf(feature.id) != -1){
                if(feature.inc_type=='money'){
                    amt+=parseFloat(feature.amount);
                }else{
                    amt+=parseFloat((feature.amount/100)*flat_rate);
                }

            }

        }
    }

    function getFeaturedCost(flat_rate){
        var additional_features = this.settings.additional_features;
        jQuery(".add-features").remove();

        var featured = this.featured;
        var amt = 0;
        for(var i=0;i<additional_features.length;i++){
            var feature = additional_features[i];
            jQuery(".add-features_"+feature.id).remove();
            if(featured.indexOf(feature.id) != -1){
                if(feature.inc_type=='money'){
                    amt+=parseFloat(feature.amount);
                    jQuery(".table-summary").append('<tr class="add-features_'+feature.id+'"><td>'+feature.name+'</td><th>$'+parseFloat(feature.amount).toFixed(2)+'</th></tr>');
                }else{
                    var calculated = parseFloat((feature.amount/100)*flat_rate);
                    jQuery(".table-summary").append('<tr class="add-features_'+feature.id+'"><td>'+feature.name+'</td><th>$'+calculated.toFixed(2)+'</th></tr>');
                    amt+=calculated;
                }

            }

        }
        return amt;
    }
    function getOrderCost(){
        var pages = jQuery("input[name='pages']").val();
        var charts = jQuery("input[name='charts']").val();
        var slides = jQuery("input[name='slides']").val();
        var spacing_val = jQuery("select[name='spacing']").val();
        var spacing = 1;
        if(spacing_val=='2'){
            spacing = 1
        }else{
            spacing = 2;
        }
        var rate = getRate();
        var cpp = getRate().cost;
        var flat_rate = cpp*spacing;
        var subject = getSubject();
        var document = getDocument();
        var language = getLanguage();
        var writer = getWriter();
        var style = getStyle();
        var subject_increase = 0;
        var document_increase = 0;

        /**
         * Calculate increment by document type
         */
        if(document.inc_type=='percent'){
            document_increase = flat_rate*((parseFloat(document.amount))/100);
        }else if(document.inc_type=='money'){
            document_increase = parseFloat(document.amount);
        }


        /**
         * Calculate increment by subject
         */
        if(subject.inc_type=='percent'){
            subject_increase = flat_rate*((parseFloat(subject.amount))/100);
        }else if(subject.inc_type=='money'){
            subject_increase = parseFloat(subject.amount);
        }

        /**
         * Calculate increment by style
         */
        if(style.inc_type=='percent'){
            style_increase = flat_rate*((parseFloat(style.amount))/100);
        }else if(style.inc_type=='money'){
            style_increase = parseFloat(style.amount);
        }

        /**
         * Calculate increment by language
         */
        if(language.inc_type=='percent'){
            language_increase = flat_rate*((parseFloat(language.amount))/100);
        }else if(language.inc_type=='money'){
            language_increase = parseFloat(language.amount);
        }

        /**
         * Calculate increment by writer
         */

        charts = parseInt(charts);
        slides = parseInt(slides);
        var charts_rate = getRate().chart;
        var ppt_rate = getRate().ppt;
        var charts_cost =charts_rate * charts;
        var ppt_cost = ppt_rate * slides;
        jQuery(".slides_count").html(slides);
        jQuery(".slides_cpp").html(ppt_rate);
        jQuery(".slides_total").html(ppt_cost);
        jQuery(".charts_count").html(charts);
        jQuery(".charts_cpp").html(charts_rate);
        jQuery(".charts_total").html(charts_cost);
        var cost_per_page = flat_rate+subject_increase+document_increase+language_increase+style_increase;
        jQuery(".pages_count").html(pages);
        jQuery(".pages_cpp").html(cost_per_page);
        var total_cost = cost_per_page*pages;
        jQuery(".pages_total").html(total_cost);
        total_cost+=charts_cost;
        total_cost+=ppt_cost;
        var featured_amt = getFeaturedCost(total_cost);
        jQuery(".table-summary").append('<tr class="add-features"><th align="centre">Total Cost   <span class=""></span> </th><th class="total_price_box_full_summary"></th></tr>')
        if(writer.inc_type=='percent'){
            writer_increase = total_cost*((parseFloat(writer.amount))/100);
        }else if(writer.inc_type=='money'){
            writer_increase = parseFloat(writer.amount);
        }
        jQuery(".writer_inc").html("$"+writer_increase.toFixed(2));
        total_cost+=writer_increase;
        total_cost+=featured_amt;
        jQuery(".total_price_box_full").html("$"+total_cost.toFixed(2));
        jQuery(".total_price_box_full_summary").html("$"+total_cost.toFixed(2));
        var deposit_rate = parseFloat('{{ @$website->deposit/100 }}');
        jQuery(".deposit_amt").html("$"+(total_cost*deposit_rate).toFixed(2)+' of $'+total_cost.toFixed(2)+'');
        jQuery("input[name='total_price']").val(total_cost.toFixed(2));
        changeCurrency();
    }

    function getSubject(){
        var subject_id = jQuery("select[name='subject_id']").val();
        var subjects = this.settings.subjects;
        var found = null;
        for(var i=0;i<subjects.length;i++){
            var subject = subjects[i];
            if(subject.id==subject_id){
                found = subject;
                break;
            }
        }
        return found;
    }
    function getWriter(){
        var id = jQuery("select[name='writer_category_id']").val();
        var writer_categories = this.settings.writer_categories;
        var writer = null;
        for(var i =0; i<writer_categories.length;i++){
            if(writer_categories[i].id==id){
                writer = writer_categories[i];
            }
        }
        return writer;
    }
    function getDocument(){
        var document_id = jQuery("select[name='document_id']").val();
        var documents = this.settings.documents;
        var found = null;
        for(var i=0;i<documents.length;i++){
            var document = documents[i];
            if(document.id==document_id){
                found = document;
                break;
            }
        }
        return found;
    }

    function getLanguage(){
        var language_id = jQuery("select[name='language_id']").val();
        var languages = this.settings.languages;
        var found = null;
        for(var i=0;i<languages.length;i++){
            var language = languages[i];
            if(language.id==language_id){
                found = language;
                break;
            }
        }
        return found;
    }

    function getStyle(){
        var style_id = jQuery("select[name='style_id']").val();
        var styles = this.settings.styles;
        var found = null;
        for(var i=0;i<styles.length;i++){
            var style = styles[i];
            if(style.id==style_id){
                found = style;
                break;
            }
        }
        return found;
    }
    function changeCurrency(){
        var selected = jQuery("#currency_select").val();
        var original = jQuery("input[name='total_price']").val();
        var currencies = this.settings.currencies;
        for(i=0;i<currencies.length;i++){
            var currency = currencies[i];
            if(currency.id==selected){
                var new_amt = parseFloat(original)*parseFloat(currency.usd_rate);
                jQuery(".total_price_box_full").html(new_amt.toFixed(2)+' '+currency.abbrev);
            }
        }
    }

    function showReturningCustomer(){
        jQuery(".cabinet-tab").removeClass('ui-tabs-active');
        jQuery(".cabinet-tab").removeClass('ui-state-active');
        jQuery("#returning_customer_tab").addClass('ui-state-active ui-tabs-active');
        jQuery("#tabs-2").slideDown();
        jQuery("#tabs-1").slideUp();
        return false;
    }

    function showNewCustomer(){
        jQuery(".cabinet-tab").removeClass('ui-tabs-active');
        jQuery(".cabinet-tab").removeClass('ui-state-active');
        jQuery("#new_customer_tab").addClass('ui-state-active ui-tabs-active');
        jQuery("#tabs-1").slideDown();
        jQuery("#tabs-2").slideUp();
        return false;
    }

    function checkId(){
        var id = jQuery("input[name='order_number']").val();
        var data = {id:id};
        $.get('{{ URL::to("order/check-number") }}',data,function(response){
            if(response == 0){

            }else{
                alert('Order Number has already been taken!');
                jQuery("input[name='order_number']").val('');
            }
        });
    }
</script>