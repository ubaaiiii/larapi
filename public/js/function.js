$(document).ready(function () {
    $(".allow_decimal").on("input", function (evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9\.]/g, ""));
        if (
            (evt.which != 46 || self.val().indexOf(".") != -1) &&
            (evt.which < 48 || evt.which > 57)
        ) {
            evt.preventDefault();
        }
    });
    $(".dark-mode-switcher").removeAttr("style");

    $(window).scroll(function () {
        if (
            $(window).scrollTop() + $(window).height() + 5 >=
            $(document).height()
        ) {
            $(".dark-mode-switcher").css("display", "none");
        } else {
            $(".dark-mode-switcher").removeAttr("style");
        }
    });

    $(document).on("keydown", ".allow-decimal", function (event) {
        if (event.shiftKey == true) {
            event.preventDefault();
        }

        if (
            (event.keyCode >= 48 && event.keyCode <= 57) ||
            (event.keyCode >= 96 && event.keyCode <= 105) ||
            event.keyCode == 8 ||
            event.keyCode == 9 ||
            event.keyCode == 37 ||
            event.keyCode == 39 ||
            event.keyCode == 46 ||
            event.keyCode == 190
        ) {
        } else {
            event.preventDefault();
        }

        if ($(this).val().indexOf(".") !== -1 && event.keyCode == 190)
            event.preventDefault();
    });
});
