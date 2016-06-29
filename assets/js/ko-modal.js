
function Ko_Modal() {

    var showModal = function(options) {
        if (typeof options === "undefined") throw new Error("An options argument is required.");
        if (typeof options.viewModel !== "object") throw new Error("options.viewModel is required.");

        var viewModel = options.viewModel;
        var template = options.template || viewModel.template;
        var context = options.context;

        if (!template) throw new Error("options.template or options.viewModel.template is required.");

        return createModalElement( template, viewModel )
            .then(jQuery)
            .then(function($ui) {
                //console.log('ESTE ES $ui');
                //console.log($ui);
                var deferredModalResult = jQuery.Deferred();
                addModalHelperToViewModel(viewModel, deferredModalResult, context);
                showTwitterBootstrapModal($ui);
                whenModalResultCompleteThenHideUI(deferredModalResult, $ui);
                whenUIHiddenThenRemoveUI($ui);
                return deferredModalResult;
            });
    };

    // Mostrar mensaje general
    // =====================================
    /*Ejemplo con mensaje
     koModal.showModalMessage('Titulo', [
     {type: 'success', text: 'Mensaje de exito'},
     {type: 'info', text: 'Mensaje de Info'},
     {type: 'warning', text: 'Mensaje de Warning'},
     {type: 'danger', text: 'Mensaje de Danger'}
     ]);*/
    var showModalMessage = function( title, messages ) {
        /*var options = {
         viewModel: new modelMessages( title, messages ),
         template: 'dialogMessage'
         };*/
        //showModal( options );
        //Llamo la funcion directamente, mas facil para que no necesite pulsar el boton para ocultarse
        var viewModel = new modelMessages( title, messages );
        var template = 'dialogMessage';
        var context = undefined;
        createModalElement( template, viewModel )
            .then(jQuery)
            .then(function($ui) {
                var deferredModalResult = jQuery.Deferred();
                addModalHelperToViewModel(viewModel, deferredModalResult, context);
                //showTwitterBootstrapModal($ui);
                $ui.modal({}); //Para que se pueda ocultar al tocar el backdrop
                whenModalResultCompleteThenHideUI(deferredModalResult, $ui);
                whenUIHiddenThenRemoveUI($ui);
                return deferredModalResult;
            });
    };

    var modelMessages = function( title, messages ) {
        var self = this;
        self.title = ko.observable(title);
        self.messages = ko.observableArray(messages);

        //if ( Object.prototype.toString.call( messages ) === '[object Array]' ) { self.messages(messages); }
        //else if ( typeof messages === 'string' ) { self.messages.push(messages); }

        self.icon = function(item) {
            return {
                'success':    'fa-check',
                'info':       'fa-info',
                'warning':    'fa-warning',
                'danger':     'fa-times'
            }[item.type];
        }

        self.cancel = function() {
            self.modal.close();
        };
    }
    // End modal para mensajes generales


    // Mostrar mensaje de confirmacion
    // =====================================
    var showModalConfirm = function( title, btn_si, btn_no, bccolor  ) {
        var viewModel = new modelConfirm( title, btn_si, btn_no, bccolor  );
        var template = 'dialogConfirm';
        var context = undefined;
        return createModalElement( template, viewModel )
            .then(jQuery)
            .then(function($ui) {
                var deferredModalResult = jQuery.Deferred();
                addModalHelperToViewModel(viewModel, deferredModalResult, context);
                //showTwitterBootstrapModal($ui);
                $ui.modal({}); //Para que se pueda ocultar al tocar el backdrop
                whenModalResultCompleteThenHideUI(deferredModalResult, $ui);
                whenUIHiddenThenRemoveUI($ui);
                return deferredModalResult;
            });
    };

    var modelConfirm = function( title, btn_si, btn_no, bccolor ) {

        var self = this;
        self.title = title;
        self.btn_si = ( undefined != btn_si ? btn_si : 'SI' );
        self.btn_no = ( undefined != btn_no ? btn_no : 'NO' );
        self.bccolor = ( undefined != bccolor ? bccolor : 'white' );

        self.accept = function() {
            self.modal.close(true);
        };

        self.cancel = function() {
            self.modal.close(false);
        };
    };


    var addModalHelperToViewModel = function (viewModel, deferredModalResult, context) {
        // Provide a way for the viewModel to close the modal and pass back a result.
        viewModel.modal = {
            close: function (result) {
                if (typeof result !== "undefined") {
                    deferredModalResult.resolveWith(context, [result]);
                } else {
                    // When result is undefined, we don't want any `done` callbacks of
                    // the deferred being called. So reject instead of resolve.
                    deferredModalResult.rejectWith(context, []);
                }
            }
        };
    };

    var showTwitterBootstrapModal = function($ui) {
        // Display the modal UI using Twitter Bootstrap's modal plug-in.
        $ui.modal({
            // Clicking the backdrop, or pressing Escape, shouldn't automatically close the modal by default.
            // The view model should remain in control of when to close.
            backdrop: "static",
            keyboard: false
        });
    };

    var whenModalResultCompleteThenHideUI = function (deferredModalResult, $ui) {
        // When modal is closed (with or without a result)
        // Then always hide the UI.
        deferredModalResult.always(function () {
            $ui.modal("hide");
        });
    };

    var whenUIHiddenThenRemoveUI = function($ui) {
        // Hiding the modal can result in an animation.
        // The `hidden` event is raised after the animation finishes,
        // so this is the right time to remove the UI element.
        $ui.on("hidden.bs.modal", function() {
            // Call ko.cleanNode before removal to prevent memory leaks.
            $ui.each(function (index, element) { ko.cleanNode(element); });
            $ui.remove();
        });
    };


    var createModalElement = function( templateName, viewModel ) {
        var temporaryDiv = addHiddenDivToBody();
        var deferredElement = jQuery.Deferred();

        ko.renderTemplate(
            templateName,
            viewModel,
            {
                afterRender: function (nodes) {
                    // Ignore any #text nodes before and after the modal element.
                    var elements = nodes.filter(function(node) {
                        return node.nodeType === 1; // Element
                    });
                    deferredElement.resolve(elements[0]); //Devuelve el primer elemento
                }
            },
            temporaryDiv,
            "replaceNode"
        );
        // Return the deferred DOM element so callers can wait until it's ready for use.
        return deferredElement;
    }

    var addHiddenDivToBody = function() {
        var div = document.createElement("div");
        div.style.display = "none";
        document.body.appendChild(div);
        return div;
    };


    return {
        showModal: showModal,
        showModalMessage: showModalMessage,
        showModalConfirm: showModalConfirm
    };

}

window.koModal = new Ko_Modal();