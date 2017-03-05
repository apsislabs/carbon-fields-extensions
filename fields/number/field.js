window.carbon = window.carbon || {};

(function($) {
    var carbon = window.carbon;
    if (typeof carbon.fields === 'undefined') { return false; }

     // Number Model
     // Code derived from https://github.com/htmlburger/carbon-fields/pull/77/files
     carbon.fields.Model.Number = carbon.fields.Model.extend({
        validate: function(attrs, options) {
            var min   = this.get('min'),
                max   = this.get('max'),
                step  = this.get('step'),
                value = attrs.value;

            // validate for step validity
            if ( step !== "any" ) {
                var testStepValidation      = ( value - min ) / step;
                var testStepValidationFloor = parseInt( testStepValidation, 10 );
                var testStepValidationCeil  = testStepValidationFloor + 1;

                if ( testStepValidation !== testStepValidationFloor ) {
                    return cfel10n.message_validation_failed_number_step
                        .replace( '%1$s', ( testStepValidationFloor * step ) + min )
                        .replace( '%2$s', ( testStepValidationCeil * step ) + min );
                }
            }

            // validate for range validity
            if ( value === '' ) {
                return crbl10n.message_required_field;
            } else if ( isNaN(value) ) {
                return crbl10n.message_form_validation_failed;
            } else if ( min > value ) {
                return cfel10n.message_validation_failed_number_min.replace( '%s', min );
            } else if ( value > max ) {
                return cfel10n.message_validation_failed_number_max.replace( '%s', max );
            }
        }
    });

    // Number View
    carbon.fields.View.Number = carbon.fields.View.extend({
        initialize: function() {
            carbon.fields.View.prototype.initialize.apply(this);
            this.on('field:rendered', this.removeRequired);
        },

        removeRequired: function() {
            $(this.el).parents('form').attr('novalidate', true);
        }
    });
}(jQuery));
