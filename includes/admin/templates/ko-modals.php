<!-- MODAL MESSAGE -->
<script id="dialogMessage" type="text/template">
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true" data-bind="click: cancel">&times;</button>
                    <h4 class="modal-title" data-bind="text: title"></h4>
                </div>
                <div class="modal-body">
                    <div data-bind="foreach: messages">
                        <div data-bind="css: 'alert-'+type" class="alert" role="alert"><i class="fa fa-fw" data-bind="css: $parent.icon($data)"></i> <span data-bind="text: text"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>


<!-- MODAL CONFIRM -->
<script id="dialogConfirm" type="text/template">
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content" data-bind="style: { backgroundColor: bccolor }">

                <div class="modal-body" style="text-align: center;">
                    <h4 class="modal-title" data-bind="text: title"></h4><br>
                    <div style="text-align: center;">
                        <a href="#" class="btn btn-success" data-bind="click: accept, text: btn_si">SI</a>
                        <a href="#" class="btn btn-danger" data-bind="click: cancel, text: btn_no">NO</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<!-- MODAL Edit menu item -->
<style>
    .dialogEditMenu label { margin: 10px 0px 2px 0px; }
</style>

<script id="dialogMenuItem" type="text/template">
    <div class="modal fade dialogMenuItem">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true" data-bind="click: cancel">&times;</button>
                    <h4 class="modal-title"><?php _e( 'Menu item' , 'erm' ); ?></h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-xs-3" data-bind="visible: type=='product'">
                            <div class="edit-image">
                                <div class="uploader-upload" data-bind="mediaUpload: {image_id: image_id, src_thumb: src_thumb, src_big: src_big, image_title: image_title, image_desc: image_desc}">
                                    <div data-bind="visible: !hasImage()">
                                        <button class="button button-default"><?php _e('Select Image', 'erm'); ?></button>
                                    </div>
                                </div>
                                <div class="uploader-image" data-bind="visible: hasImage">
                                    <img data-bind="attr: {src: src_thumb}">
                                    <i class="icon-delete fa fa-times" data-bind="click: removeimage"></i>
                                </div>
                            </div>
                        </div>

                        <div class="" data-bind="css: {'col-xs-9': type=='product', 'col-xs-12': type=='section'}">
                            <div class="form-group">
                                <label><?php _ex('Title','modal window','erm'); ?></label>
                                <input type="text" class="form-control" data-bind="value: title">
                            </div>
                            <div class="form-group">
                                <label><?php _ex('Description','modal window', 'erm'); ?></label>
                                <textarea class="form-control" id="modalCkeditor" data-bind="ckEditor: content"></textarea>
                            </div>
                            <div class="form-group" data-bind="visible: type=='product'">
                                <label><?php _ex('Prices','modal window', 'erm'); ?></label>
                                <div class="edit-prices" data-bind="sortable: {data:prices, options:{axis:'y', handle:'.icon-move-price'}}">
                                    <div class="item-price">
                                        <i class="icon-move-price fa fa-bars"></i>
                                        <input placeholder="<?php _e('Title', 'erm'); ?>" data-bind="value: name">
                                        <input placeholder="<?php _e('Price', 'erm'); ?>" data-bind="value: value">
                                        <i class="icon-delete-price fa fa-times" data-bind="click: $parent.removeprice"></i>
                                    </div>
                                </div>
                                <button class="add-price button button-default button-large" data-bind="click: newprice"><?php _e( 'Add price' , 'erm' ); ?></button>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-primary" data-bind="click: ok"><?php _e( 'Save' , 'erm' ); ?></a>
                    <a href="#" class="btn btn-primary" data-bind="click: cancel"><?php _e( 'Cancel' , 'erm' ); ?></a>
                </div>

            </div>
        </div>
    </div>
</script>
<script>
    function ModelDialogMenuItem( type, title, content, image, prices ) {

        var self = this;
        self.type = type;
        self.title = ko.observable( title );
        self.content = ko.observable( content );
        self.prices = ko.observableArray( [] );
        jQuery.each( prices, function(index, item){
            self.prices.push({
                name: item.name,
                value: item.value
            });
        });

        self.image_id = ko.observable( image.id );
        self.image_title = ko.observable( image.title );
        self.image_desc = ko.observable( image.desc );
        self.src_thumb = ko.observable( image.src_thumb );
        self.src_big = ko.observable( image.src_big );
        self.hasImage = ko.computed( function(){
            return self.image_id() != 0;
        });

        self.removeimage = function(){
            self.image_id(0);
            self.src_thumb('');
            self.src_big('');
        };

        self.newprice = function(){
            self.prices.push({
                name: '',
                value: ''
            });
        };

        self.removeprice = function(item){
            self.prices.remove(item);
        };

        self.ok = function() {
            this.modal.close({
                title: self.title(),
                content: self.content(),
                image: {
                    id: self.image_id(),
                    title: self.image_title(),
                    desc: self.image_desc(),
                    src_thumb: self.src_thumb(),
                    src_big: self.src_big()
                },
                prices: self.prices()
            });
        };

        self.cancel = function() { this.modal.close(); }
    };
</script>