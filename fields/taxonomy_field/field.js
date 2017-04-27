window.carbon = window.carbon || {};

(function($) {
    var carbon = window.carbon;
    if (typeof carbon.fields === 'undefined') { return false; }

    carbon.fields.View.Taxonomy = carbon.fields.View.extend({
        initialize: function() {
            carbon.fields.View.prototype.initialize.apply(this);
            this.on('field:rendered', this.initChosen);
        },

        initChosen: function() {
            $(this.el).find('select').chosen({
                search_contains: true,
                allow_single_deselect: true,
                width: "100%"
            });
        }
    });
}(jQuery));
