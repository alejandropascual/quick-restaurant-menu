jQuery(document).ready(function($){

    // BINDINGS

    // Marcar los dias de la semana en forma de array (lunes a domingo)
    ko.bindingHandlers.diasSemana = {

        init: function (element, valueAccessor, allBindings, viewModel, bindingContext) {

            var observable = valueAccessor();
            var $el = jQuery(element);

            var week_days = allBindings.get('week_days');

            $el.html('\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d0"><span>'+week_days[0]+'</span></label></div>\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d1"><span>'+week_days[1]+'</span></label></div>\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d2"><span>'+week_days[2]+'</span></label></div>\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d3"><span>'+week_days[3]+'</span></label></div>\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d4"><span>'+week_days[4]+'</span></label></div>\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d5"><span>'+week_days[5]+'</span></label></div>\
			<div class="checkbox ch-week"><label><input type="checkbox" name="d6"><span>'+week_days[6]+'</span></label></div>');

            if ( observable().length == 7 ) {
                jQuery.each( observable(), function(index,item){
                    $el.find('[name=d'+index+']').attr('checked',item);
                });
            }

            $el.on('change', 'input', function(ev){
                var valores = [];
                jQuery.each( $el.find('input'), function(index,item){
                    if ( jQuery(item).is(':checked') ) {
                        valores.push(true);
                    } else {
                        valores.push(false);
                    }
                } );
                observable(valores);
            });

        }
    };


    // Marcar una franja horario de 00:00 a 24:00 cada 15 minutos
    ko.bindingHandlers.franjaHoraria = {

        init: function (element, valueAccessor, allBindings, viewModel, bindingContext) {

            var $el = jQuery(element);

            var observable = valueAccessor();
            var begin = observable.begin();
            var end = observable.end();
            begin = begin == '' ? '10:00' : begin;
            end = end == '' ? '23:00' : end;

            var number_to_hour = function(num) {
                var h = parseInt(num/4);
                var m = 15*(num-h*4);
                h = h<10 ? '0'+h : h;
                m = m<10 ? '0'+m : m;
                return h+':'+m;
            };

            var hour_to_number = function(hour) {
                //'12:50';
                var hm = hour.split(':');
                var h = parseInt(hm[0]);
                var m = parseInt(hm[1]);
                var n = h*4+m/15;
                return n;
            };

            $el.ionRangeSlider({
                type: "double",
                min: 0,
                max: 24*4, // Cada 15 minutos
                from: hour_to_number(begin),
                to: hour_to_number(end),
                keyboard: true,
                grid: true,
                grid_snap: true,
                prettify: function (num) {
                    return number_to_hour(num);
                },
                onStart: function (data) {
                },
                onChange: function (data) {
                    observable.begin( number_to_hour( data.from ));
                    observable.end( number_to_hour( data.to ) );
                },
                onFinish: function (data) {

                },
                onUpdate: function (data) {
                }
            });

        }
    };


    // APP
    function FranjaHoraria( item, menus, week_days ) {

        var self = this;

        self.week_days = week_days;
        self.menus = menus;
        self.menu = ko.observable();
        jQuery.each(self.menus, function(index,el){
           if ( item.menu_id == el.id ) {
               self.menu(el);
           }
        });

        // Pasar de 'true' y 'false' a true y false
        var week = [];
        jQuery.each( item.week, function(index,item) {
            week.push( (item === 'true' || item === true) ? true : false );
        });
        self.week = ko.observableArray(week).extend({ rateLimit: 500 });

        self.horario = {
            begin: ko.observable(item.begin).extend({ rateLimit: 500 }),
            end: ko.observable(item.end).extend({ rateLimit: 500 })
        };

        self.changed = function(){
            trigger.notifySubscribers('', 'save_franjas');
        };

        setTimeout( function(){
            self.week.subscribe( function(item){ self.changed(); });
            self.horario.begin.subscribe( function(item){ self.changed(); });
            self.horario.end.subscribe( function(item){ self.changed(); });
            self.menu.subscribe( function(item){ self.changed(); });
        }, 100);
    }

    function Horarios( d ) {

        var self = this;
        self.spin = ko.observable(false);

        self.id = d.post_id;
        self.menus = d.menus;
        self.week_days = d.week_days;

        self.franjas = ko.observableArray([]);
        jQuery.each(d.franjas, function (index, item) {
            self.franjas.push(new FranjaHoraria(item, self.menus, self.week_days));
        });

        self.newFranja = function () {
            self.franjas.push(
                new FranjaHoraria({
                    week: [false, false, false, false, false, false, false],
                    begin: '10:00',
                    end: '22:00',
                    menu: 0
                }, self.menus, self.week_days )
            );
            self.save();
        }

        self.removeFranja = function( item ){
            self.franjas.remove( item );
            self.save();
        }

        self.afterMove = function(){
            self.save();
        }

        trigger.subscribe( function(){
            self.save();
        },self, 'save_franjas');

        self.save = function(){
            if ( self.requesting ) { self.requesting.abort(); }

            self.spin(true);

            var data = {
                action: 'erm_update_menu_week',
                post_id: self.id,
                franjas: []
            };

            jQuery.each( self.franjas(), function(index,item){

                data.franjas.push({
                    week: item.week(),
                    begin: item.horario.begin(),
                    end: item.horario.end(),
                    menu_id: item.menu().id
                });
            });

            self.requesting = jQuery.post(ajaxurl, data, function(response){
                //console.log(response);
                if (response.success) {

                }
                self.spin(false);
            });

        }

    }// End Horarios

    var trigger = new ko.subscribable();
    ko.applyBindings( new Horarios( erm_vars ),  document.getElementById('horarios') );
});