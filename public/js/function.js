function paginatioon(table, halaman) {
    var info = table.page.info();
    halaman.html("");

    var _pagefirstt = info.page == 0 ? "disabled" : "",
        _pagefirst = info.page == 0 ? "style='display:none;'" : "",
        _pagemin3 =
        info.page - 3 <= 0 || info.page + 1 - (info.page - 3) > 2 ?
        "style='display:none;'" :
        "",
        _pagemin2 =
        info.page - 2 <= 0 || info.page + 1 - (info.page - 2) > 2 ?
        "style='display:none;'" :
        "",
        _pagemin1 =
        info.page - 1 <= 0 || info.page + 1 - (info.page - 1) > 2 ?
        "style='display:none;'" :
        "",
        _page =
        info.page <= 0 || info.page + 1 - info.page > 2 ?
        "style='display:none;'" :
        "",
        _pageplus2 =
        info.page + 2 > info.pages ||
        info.page + 2 - (info.page + 1) > 2 ?
        "style='display:none;'" :
        "",
        _pageplus3 =
        info.page + 3 > info.pages ||
        info.page + 3 - (info.page + 1) > 2 ?
        "style='display:none;'" :
        "",
        _pageplus4 =
        info.page + 4 > info.pages ||
        info.page + 4 - (info.page + 1) > 2 ?
        "style='display:none;'" :
        "",
        _pageplus5 =
        info.page + 5 > info.pages ||
        info.page + 5 - (info.page + 1) > 2 ?
        "style='display:none;'" :
        "",
        _pagelast =
        info.page + 1 == info.pages ||
        info.page + 2 - (info.page + 1) > 2 ?
        "style='display:none;'" :
        "",
        _pagelastt =
        info.page + 1 == info.pages ||
        info.page + 2 - (info.page + 1) > 2 ?
        "disabled" :
        "";

    var pageAwal =
        `<li>
                                    <button class="pagination__link gotoPage" ` +
        _pagefirstt +
        ` d-page="first"> <i class="w-4 h-4"
                                            data-feather="chevrons-left"></i> </button>
                                </li>
                                <li>
                                    <button class="pagination__link gotoPage" ` +
        _pagefirstt +
        ` d-page="previous"> <i class="w-4 h-4"
                                            data-feather="chevron-left"></i> </button>
                                </li>`,
        pageAkhir =
        `<li>
                                    <button class="pagination__link gotoPage" ` +
        _pagelastt +
        ` d-page="next"> <i class="w-4 h-4"
                                            data-feather="chevron-right"></i> </button>
                                </li>
                                <li>
                                    <button class="pagination__link gotoPage" ` +
        _pagelastt +
        ` d-page="last"> <i class="w-4 h-4"
                                            data-feather="chevrons-right"></i> </button>
                                </li>`;

    halaman.html(
        pageAwal +
        `<li> <button class="pagination__link" disabled style="display:none;">...</button> </li>
                <li> <button class="pagination__link" disabled ` +
        _pagefirst +
        `>...</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page - 4) +
        `" ` +
        _pagemin3 +
        `>` +
        (info.page - 3) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page - 3) +
        `" ` +
        _pagemin2 +
        `>` +
        (info.page - 2) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page - 2) +
        `" ` +
        _pagemin1 +
        `>` +
        (info.page - 1) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page - 1) +
        `" ` +
        _page +
        `>` +
        info.page +
        `</button> </li>
                <li> <button class="pagination__link pagination__link--active" d-page="` +
        info.page +
        `">` +
        (info.page + 1) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page + 1) +
        `" ` +
        _pageplus2 +
        `>` +
        (info.page + 2) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page + 2) +
        `" ` +
        _pageplus3 +
        `>` +
        (info.page + 3) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page + 3) +
        `" ` +
        _pageplus4 +
        `>` +
        (info.page + 4) +
        `</button> </li>
                <li> <button class="pagination__link gotoPage" d-page="` +
        (info.page + 4) +
        `" ` +
        _pageplus5 +
        `>` +
        (info.page + 5) +
        `</button> </li>
                <li> <button class="pagination__link" disabled ` +
        _pagelast +
        `>...</button> </li>` +
        pageAkhir
    );
}

function gotoPage(e, table) {
    var page = e.attr("d-page");
    if (!isNaN(page)) {
        page = parseInt(page);
    }
    // console.log(page);
    table.page(page).draw("page");
}
$(document).ready(function() {

    $(".dark-mode-switcher").removeAttr("style");

    $(window).scroll(function() {
        if (
            $(window).scrollTop() + $(window).height() + 5 >=
            $(document).height()
        ) {
            $(".dark-mode-switcher").css("display", "none");
        } else {
            $(".dark-mode-switcher").removeAttr("style");
        }
    });

    $("select").on("select2:open", function() {
        // uppercase select2 search
        $(".select2-search__field").css("text-transform", "uppercase");
    });

    $(".currency").inputmask("decimal", {
        alias: "numeric",
        groupSeparator: ",",
        autoGroup: true,
        digits: 2,
        radixPoint: ".",
        digitsOptional: false,
        placeholder: "0",
    });

    $(".decimal").inputmask("decimal", {
        alias: "numeric",
        groupSeparator: ",",
        autoGroup: true,
        digits: 0,
        radixPoint: ".",
        digitsOptional: false,
        allowMinus: false,
        placeholder: "0",
    });

    $(".tsi").keyup(function() {
        var sum = 0;
        $(".tsi").each(function() {
            if ($(this).val() == "") {
                sum += 0;
            } else {
                sum += parseFloat($(this).inputmask("unmaskedvalue"));
            }
        });
        $(".total-si").val(sum).trigger('change');
    });

    $(".masked").keyup(function() {
        $("[name='" + $(this).attr("id") + "']").val(
            $(this).inputmask("unmaskedvalue")
        );
    });
    $(".date-range").inputmask("99/99/9999 - 99/99/9999");
});