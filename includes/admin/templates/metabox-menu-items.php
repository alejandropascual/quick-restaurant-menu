<?php include('ko-modals.php'); ?>

<div id="erm_list_menu_items">
    <i class="page-spin fa fa-refresh fa-spin" data-bind="visible: spin"></i>
    <div class="erm_menu_items" data-bind="sortable: {data:menu_items, options:{axis:'y', handle:'.icon-move'}, afterMove: aftermove}">
        <div class="erm_menu_item" data-bind="css: {'item-hidden': !visible(), 'product': is_product(), 'section': is_section()}">
            <table style="width: 100%;">
                <tr>
                    <td style="width:40px;"><i class="fa fa-bars icon-move"></i></td>
                    <td style="width:80px;"><a class="image-popup" href="#" data-bind="visible: hasImage, click: show_popup_image"><img class="src_thumb" data-bind="attr: {src: src_thumb}"></a></td>
                    <td style="position: relative;">
                        <span class="title" data-bind="text: title"></span>
                        <!-- input style="width: 100%;" class="input-title" data-bind="value: title, css:{'visible': editing()}" -->
                        <div class="description" data-bind="html: content"></div>
                        <div class="prices" data-bind="foreach: prices">
                            <div><span data-bind="text: name"></span> <span data-bind="text: value"></span> | </div>
                        </div>
                    </td>
                    <td style="width:120px;">
                        <div class="edit-icons">
                            <i class="fa" data-bind="visible: !is_section(), css: visible_css, click: toggle_visible"></i>
                            <!-- i class="fa" data-bind="css: editing_css, click: toggle_editing"></i -->
                            <i class="fa" data-bind="css: editing_css, click: popup_editor"></i>
                            <i class="fa fa-remove" data-bind="click: $parent.removeitem"></i>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- div class="small-content">
                <div data-bind="html: content"></div>
                <div data-bind="foreach: prices">
                    <div><span data-bind="text: name"></span> <span data-bind="text: value"></span></div>
                </div>
            </div -->

        </div>
    </div>
    <div data-bind="if: post_created()">
        <button class="button button-default button-large" data-bind="click: newitem_product"><?php _e('New Menu Item','erm')?></button>
        <button class="button button-default button-large" data-bind="click: newitem_section"><?php _e('New Title Section','erm')?></button>
        <!-- a class="button button-default button-large" data-bind="click: add_menuitem"><?php //_e('Add Menu Item','erm')?></a -->
    </div>
    <div data-bind="if: !post_created()">
        <h3><?php _e('Please, SAVE THIS POST to begin adding Menu Items','erm'); ?></h3>
    </div>

</div>

<div id="dialog-confirm" title="Empty the recycle bin?" style="display: none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span></p>
</div>

<div id="dialog-select-menuitem">

</div>