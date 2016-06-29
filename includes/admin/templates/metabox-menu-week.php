<style>
    #horarios .page-spin {
        position: absolute;
        top: -45px;
        right: 50px;
    }

    .franja_horaria {
        position: relative;
        padding: 10px 20px;
        border: 1px solid #D8D7D7;
        border-radius: 10px;
        margin-bottom: 10px;
        text-align: left;
        background: #D8D7D7;
    }

    .franja_horaria .semana { margin: 10px 20px 20px 40px; }
    .franja_horaria .horario { margin-top: 10px; }
    .franja_horaria .fa-times { position: absolute; top: 15px; right: 20px; }
    .franja_horaria .fa-bars { position: absolute; top: 15px; left: 20px; }
    .franja_horaria .fa-times { cursor: pointer; }
    .franja_horaria .fa-bars { cursor: crosshair; }
    .franja_horaria .fa:hover { color:red; }

    .franja_horaria.ui-sortable-placeholder { min-height: 180px; visibility: visible !important; background: white; }

    .irs-from,.irs-to,.irs-single { font-weight: bold; }

    .checkbox.ch-week {
        display: inline-block;
        margin-right: -10px;
    }
    .checkbox label, .radio label {
        min-height: 20px;
        padding-left: 20px;
        margin-bottom: 0;
        font-weight: 400;
        cursor: pointer;
    }
    .checkbox input[type=checkbox] {
        position: absolute;
        margin-left: 10px;
        margin-top: 5px;
        z-index: -1;
    }
    .ch-week span {
        background: #efefef;
        color: #ababab;
        padding: 5px 15px;
        border-radius: 10px;
    }
    .ch-week input:checked + span {
        background: #428bca;
        color: white;
    }
</style>

<div id="horarios">

    <i class="page-spin fa fa-refresh fa-spin" data-bind="visible: spin"></i>

    <div style="margin: 20px 20px;">
        <div><?php _e( '- Create schedules and assign a menu to each of them.' , 'erm' ); ?></div>
        <div><?php _e( '- Insert the shortcode in any post or page.' , 'erm' ); ?></div>
        <div><?php _e( '- The page/post will show the first menu that satisfied the rule.' , 'erm' ); ?></div>
        <br>
        <div>- <?php _e('UTC time:','erm'); echo ' '.current_time( 'l, d F Y - H:i', 1 ); ?></div>
        <div>- <?php _e('LOC time:','erm'); echo ' <strong>'.current_time( 'l, d F Y - H:i', 0 ).'</strong> ' . __('( Use local time to select the time interval. )','erm'); ?></div>
    </div>

    <div data-bind="sortable: {data:franjas,options:{axis:'y', handle:'.icon-move'}, afterMove: afterMove}">
        <div class="franja_horaria">
            <i class="icon-move fa fa-bars"></i>
            <div class="semana" data-bind="diasSemana: week, week_days:week_days"></div>
            <div class="horario"><div data-bind="franjaHoraria: horario"></div></div>
            <div style="margin-top:20px;"><?php _e('SELECT MENU:','erm'); ?> <select data-bind="options:menus, optionsText:'title', value:menu"></select></div>
            <i class="icon-delete fa fa-times" data-bind="click: $parent.removeFranja"></i>
        </div>
    </div>
    <button data-bind="click: newFranja" class="button button-default button-large"><?php _e( 'Add schedule' , 'erm' ); ?></button><br>


</div>

