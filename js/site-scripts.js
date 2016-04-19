jQuery(document).ready(function () {
    //форма обратной связи
    jQuery('#callback-button').click(function (e) {
        var callback_form = jQuery('#center-panel');

        if (callback_form.css('display') != 'block') {
            callback_form.show();
            jQuery('#callback-button');

            var firstClick = true;

            jQuery(document).on("click", function (e) {
                if (!firstClick && jQuery(e.target).closest('#center-panel').length == 0) {
                    callback_form.hide();

                    jQuery('#callback-button');

                    jQuery(document).off("click");
                }
                firstClick = false;
            });

        }

        e.preventDefault();
    });
});
