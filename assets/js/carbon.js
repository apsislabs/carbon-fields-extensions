window.carbon = window.carbon || {};

(function($) {
    var carbon = window.carbon;
    if (typeof carbon.fields === 'undefined') { return false; }

    /*
    |--------------------------------------------------------------------------
    | Select, Taxonomy, and RelatedPost Field VIEW
    |--------------------------------------------------------------------------
    */
    carbon.fields.View.Select = carbon.fields.View.extend({
        initialize: function() {
            carbon.fields.View.prototype.initialize.apply(this); // do not delete
            this.on('field:rendered', this.initField);
        },

        initField: function() {
            $(this.el).find('select').chosen({
                search_contains: true,
                width: "100%"
            });
        }
    });

    carbon.fields.View.PostObject = carbon.fields.View.Select.extend();
    carbon.fields.View.Taxonomy = carbon.fields.View.Select.extend();
}(jQuery));
