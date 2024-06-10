<?php
$tab = @$_GET['tab'];
if($tab == ''){
    $tab = $tabs[0];
}
?>
<div class="tabpanel">
    <ul class="tab-nav" role="tablist">
        @foreach($tabs as $single_tab)
            <li class="{{ $tab==$single_tab ? 'active':'' }}">
                <a class="load-page" href="{{ url($base_url."?tab=".$single_tab)  }}">{{ ucwords(str_replace('_',' ',$single_tab)) }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        <div class="section">
            @include($tabs_folder.".".$tab)
        </div>
    </div>
</div>