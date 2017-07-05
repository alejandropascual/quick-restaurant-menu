
jQuery(document).ready(function($){

    /////////////////////////////////////////////
    // Bindings
    /////////////////////////////////////////////

    ko.bindingHandlers.slideVisible = {

        init: function( element, valueAccessor ) {

            var value = ko.utils.unwrapObservable(valueAccessor());

            if (!value) { jQuery(element).hide(); }
        },

        update: function( element, valueAccessor ) {

            var value = ko.utils.unwrapObservable(valueAccessor());

            if (value) {
                jQuery(element).slideDown();
            } else {
                jQuery(element).slideUp();
            }
        }
    }

    ko.bindingHandlers.tinyMCE = {

        init: function( element, valueAccessor ) {

            //console.log('Arrancando tinymce');

            var value = ko.utils.unwrapObservable(valueAccessor());
            element.innerHTML = value;

            tinymce.init({
                mode:      "exact",
                elements : element.id,
                menubar: false,
                content_css: erm_vars.editor_css
                //toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                //toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
                //toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft"
            });

        },
        update: function( element, valueAccessor ) {

        }
    }

    ko.bindingHandlers.ckEditor = {

        init: function( element, valueAccessor ) {

            var value = ko.utils.unwrapObservable(valueAccessor());
            element.innerHTML = value;
            CKEDITOR.config.resize_enabled = false;
            //CKEDITOR.config.removeButtons = 'Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript,addFile,Image,Table,Styles,Format,Maximize,HorizontalRule,Unlink,Blockquote,Indent,Outdent,RemoveFormat,Source,Spell';
            CKEDITOR.config.removePlugins = 'save,print,preview,find,about,maximize,showblocks,image,scayt';
            CKEDITOR.config.allowedContent = true;
            $(element).ckeditor();
            var id = $(element).attr('id');
            var editor = CKEDITOR.instances[id];

            editor.on('change', function( data ){
                //console.log( editor.getData() );
                valueAccessor()( editor.getData() );
            });

        },
        update: function( element, valueAccessor ) {

        }
    }




    ko.bindingHandlers.mediaUpload = {

        init: function( element, valueAccessor ) {

            var valueAccessor = valueAccessor;

            jQuery(element).find('button').on('click', function(ev){

                ev.preventDefault();

                // WP 3.5+ uploader
                var file_frame = undefined;

                // New media uploader wp >= 3.5
                if (erm_vars.use_new_media_35 == 1) {

                    if ( undefined != file_frame ) {
                        file_frame.open();
                        return;
                    }

                    file_frame = wp.media.frames.file_frame = wp.media({
                        frame: 'post',
                        state: 'insert',
                        //title: 'Insert image',
                        //button: { text: 'Select image' },
                        multiple: false
                    });

                    file_frame.on('insert', function(){

                        var selection = file_frame.state().get('selection');
                        selection.each( function( attachment, index ){

                            attachment = attachment.toJSON();
                            var url = attachment.url;
                            var src_thumb = url.replace(/\.([a-z]{3,4})$/,'-150x150.$1');

                            valueAccessor().image_id( attachment.id );
                            valueAccessor().src_thumb( src_thumb );
                            valueAccessor().src_big( attachment.url );
                            valueAccessor().image_title( attachment.caption );
                            valueAccessor().image_desc( attachment.description );

                        });
                    });

                    file_frame.open();

                }

                // Old media uploader
                else {

                }

            });

        },
        update: function( element, valueAccessor ) {

        }
    }


    /////////////////////////////////////////////
    // Menu items
    /////////////////////////////////////////////
    function MenuCarta_item( menu_id, el, spin ) {

        var self = this;
        self.menu_id = menu_id;
        self.spin = spin;

        self.id = el.id;
        self.link = el.link.replace('&amp;','&');
        self.type = el.type;
        self.is_product = function() { return self.type == 'product'; }
        self.is_section = function() { return self.type == 'section'; }
        self.title = ko.observable( el.title );
        self.content = ko.observable( el.content );

        self.image_id = ko.observable( el.image_id );
        self.image_title = ko.observable( el.image_title );
        self.image_desc = ko.observable( el.image_desc );
        self.src_thumb = ko.observable( el.src_thumb );
        self.src_big = ko.observable( el.src_big );
        self.hasImage = ko.computed( function(){
            return self.image_id() != 0;
        });

        self.removeimage = function(){
            self.image_id(0);
            self.src_thumb('');
            self.src_big('');
        };

        self.show_popup_image = function(){
            $.magnificPopup.open({
                items: {
                    src: self.src_big()
                },
                type: 'image',
                //closeOnContentClick: true,
                //closeBtnInside: false,
                //fixedContentPos: true,
                mainClass: 'mfp-no-margins', // class to remove default margin from left and right side
                image: {
                    verticalFit: true,
                    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                    titleSrc: function(item) {
                        return self.image_title() + '<small>' + self.image_desc() + '</small>';
                    }
                }
            });
        };


        // Product vivible
        self.visible = ko.observable( el.visible==1 ? true : false );
        self.visible_css = ko.computed( function(){
            return self.visible() ? 'fa-eye' : 'fa-eye-slash';
        });
        self.toggle_visible = function(){
            self.visible( !self.visible() );
            self.save();
        };

        // Edit content
        self.editing = ko.observable(false);
        self.editing_css = ko.computed( function(){
            return self.editing() ? 'fa-save' : 'fa-pencil';
        });
        self.toggle_editing = function(){
            self.editing( !self.editing() );
            if ( !self.editing() ) {
                self.content( tinyMCE.get(self.editor_id).getContent() );
                self.save();
            }
        };

        // Editor tinyMCE
        self.editor_id = 'editor_'+self.id;

        //Prices
        self.prices = ko.observableArray([]);
        if ( null !== el.prices && el.prices.length) {
            //if ( el.prices.length) {
            jQuery.each(el.prices, function(index, item){
                self.prices.push({
                    name: item.name,
                    value: item.value
                });
            });
        }

        self.newprice = function(){
            self.prices.push({
                name: '',
                value: ''
            });
        }
        self.removeprice = function(item){
            self.prices.remove(item);
        };


        self.save = function(){

            //console.log('Saving erm_update_menu_item');

            //if ( self.requesting ) { self.requesting.abort(); }

            self.spin(true);
            var data = {
                action: 'erm_update_menu_item',
                post_id: self.id,
                title: self.title(),
                content: self.content(),
                image_id: self.image_id(),
                visible: self.visible(),
                prices: []
            }

            if (self.prices().length) {
                jQuery.each( self.prices(), function(index, price) {
                    data.prices.push({name: price.name, value: price.value});
                });
            }

            self.requesting = jQuery.post(ajaxurl, data, function(response){
                if (response.success) {
                }
                self.spin(false);
            });
        };

        //setTimeout( function(){
        //    self.content.subscribe( self.save );
        //    self.image_id.subscribe( self.save );
        //    self.visible.subscribe( self.save );
        //    self.title.subscribe( self.save );
        //}, 100);

        // Popup editor - new
        self.popup_editor = function(){

            koModal.showModal({
                template: 'dialogMenuItem',
                viewModel: new ModelDialogMenuItem( self.type, self.title(), self.content(), {
                    id: self.image_id(),
                    title: self.image_title(),
                    desc: self.image_desc(),
                    src_thumb: self.src_thumb(),
                    src_big: self.src_big()
                }, self.prices() )
            }).done(function(result){

                //console.log( result );

                self.title( result.title );
                self.content( result.content );
                self.image_id( result.image.id );
                self.image_title( result.image.title );
                self.image_desc( result.image.desc );
                self.src_thumb( result.image.src_thumb );
                self.src_big( result.image.src_big );

                //console.log( self.prices() );
                self.prices( result.prices );
                //console.log( self.prices() );

                self.save();
            });


        };
    }

    function MenuCarta( el ) {

        var self = this;
        self.spin = ko.observable(false);

        self.id = el.menu_id;
        self.post_created = function(){
            return self.id != null;
        };
        self.menu_items = ko.observableArray( [] );

        if ( null !== el.menu_items && el.menu_items.length) {
            jQuery.each( el.menu_items, function(index,item) {
                //console.log(item);
                self.menu_items.push( new MenuCarta_item( self.id, item, self.spin ) );
            } );
        }

        self.aftermove = function(){
            self.save();
        }

        self.newitem = function( type ) {

            //if ( self.requesting_new ) { self.requesting_new.abort(); }

            self.spin(true);

            var data = {
                action: 'erm_create_menu_item',
                type: type
            }
            self.requesting_new = jQuery.post(ajaxurl, data, function(response){

                if (response.success) {

                    var newitem = new MenuCarta_item( self.id, response.data, self.spin );
                    self.menu_items.push( newitem );

                    //newitem.editing(true);
                    setTimeout( self.save, 100 );

                    // Open popup to edit the menu item
                    newitem.popup_editor();

                } else {
                    alert('Error');
                }
                self.spin(false);
            });
        };

        self.newitem_product = function() {
            self.newitem('product');
        };

        self.newitem_section = function() {
            self.newitem('section');
        };

        self.removeitem = function( item ){

            koModal.showModalConfirm( 'Are you sure to delete this item ?', 'YES', 'NO', '#eaeaea'  )
                .done( function( response ) { if ( response ) {

                    var item_id = item.id;

                    //if ( self.requesting ) { self.requesting.abort(); }

                    self.spin(true);
                    var data = {
                        action: 'erm_delete_menu_item',
                        post_id: item_id
                    }
                    self.requesting = jQuery.post(ajaxurl, data, function(response){
                        self.spin(false);
                        if (response.success) {
                            self.menu_items.remove( item );
                            setTimeout( self.save, 100 );
                        }
                    });

                } } );

            /*sweetAlert({
             title: erm_vars.notices.alert_delete,
             type: 'warning',
             showCancelButton: true,
             confirmButtonText: erm_vars.notices.alert_confirm,
             confirmButtonColor: 'rgb(74, 196, 234)',
             closeOnConfirm: true
             }, function(){

             var item_id = item.id;

             if ( self.requesting ) { self.requesting.abort(); }
             self.spin(true);
             var data = {
             action: 'erm_delete_menu_item',
             post_id: item_id
             }
             self.requesting = jQuery.post(ajaxurl, data, function(response){
             self.spin(false);
             if (response.success) {
             self.menu_items.remove( item );
             setTimeout( self.save, 100 );
             }
             });
             });
             */

        };

        self.add_existingitem = function(){
            alert('Add existing Menu Item');
        };

        self.ordenitems = function(){
            var string = '';
            jQuery.each( self.menu_items(), function(index,item){
                string += item.id + ',';
            });
            if (string.length > 0) { string = string.slice(0,-1); }
            return string;
        }

        self.add_menuitem = function(){

            //if ( self.requesting ) { self.requesting.abort(); }

            self.spin(true);
            var data = {
                action: 'erm_list_menu_items'
            }
            self.requesting = jQuery.post(ajaxurl, data, function(response){
                self.spin(false);
                //console.log(response);
                if (response.success) {
                    self.popup_menuitems( response.data );
                }
            });



        }

        self.popup_menuitems = function( data ){

            var html = data.html;
            var json_items = data.items;

            $.magnificPopup.open({
                items: {
                    type: 'inline',
                    src: $('<div id="popup-select-items">'+html+'</div>')
                },
                type: 'inline',
                midClick: true,
                overflowY: 'scroll',
                callbacks: {
                    open: function(){
                        this.content.on('click','#add-menu-items', function(){

                            var items = $('#popup-select-items input:checked');
                            $.magnificPopup.close();

                            if ( items.length ) {
                                var json_selected = [];
                                for( var i=0; i<items.length; i++) {
                                    var id = $(items[i]).attr('data-id');
                                    $.each(json_items, function(index,item){
                                        if ( id == item.id ) {
                                            json_selected.push( item );
                                        }
                                    })
                                }
                                self.add_selected_items( json_selected );
                            }

                        });
                    },
                    close: function(){
                        this.content.off('click');
                    }
                }
            });
        }

        self.add_selected_items = function( items ){
            $.each(items, function(index,item){
                self.menu_items.push( new MenuCarta_item( self.id, item, self.spin ) );
            });
            self.save();
        };

        self.save = function(){

            //console.log('Saving order menu items');

            /*if ( self.requesting ) {
                console.log('Canceling ajax request');
                self.requesting.responseText = '';
                self.requesting.abort();
                self.requesting = null;
            }*/

            self.spin(true);
            var data = {
                action: 'erm_update_list_menu_items',
                post_id: self.id,
                ids: self.ordenitems()
            }
            self.requesting = jQuery.post(ajaxurl, data, function(response){

                // response is the .responseText
                //console.log( response );

                if (response.success) {

                }
                self.spin(false);
            });

            //console.log(self.requesting);
        }
    }


    ko.applyBindings( new MenuCarta( erm_vars ),  document.getElementById('erm_list_menu_items') );
    $('#erm_list_menu_items').fadeIn(400);

    //tinymce.init({selector:'.menu_item_desc'});
});