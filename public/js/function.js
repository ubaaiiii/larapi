$(document).ready(function () {
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

    $("select").on("select2:open", function () {
        // uppercase select2 search
        $(".select2-search__field").css("text-transform", "uppercase");
    });
    $("select").keyup(function () {
        console.log("change");
    });

    $(".currency").inputmask("decimal", {
        alias: "numeric",
        groupSeparator: ",",
        autoGroup: true,
        digits: 2,
        radixPoint: ".",
        digitsOptional: false,
        allowMinus: false,
        placeholder: "0",
    });

    $(".tsi").keyup(function () {
        var sum = 0;
        $(".tsi").each(function () {
            if ($(this).val() == "") {
                sum += 0;
            } else {
                sum += parseFloat($(this).inputmask("unmaskedvalue"));
            }
        });
        $("#tsi").val(sum);
    });
});
