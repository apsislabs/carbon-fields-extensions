window.carbon = window.carbon || {};

(function($) {
    var carbon = window.carbon;
    if (typeof carbon.fields === 'undefined') { return false; }

    carbon.fields.View.Money = carbon.fields.View.extend({
        initialize: function() {
            carbon.fields.View.prototype.initialize.apply(this);
            this.on('field:rendered', this.initMaskMoney);
        },

        initMaskMoney: function() {
            $(this.el).find('.input-money')
                .maskMoney()
                .maskMoney('mask');
        }
    });
}(jQuery));
