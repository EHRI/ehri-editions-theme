jQuery(function ($) {
    $("#document-text")
        .tabs({
            show: false,
            active: 0,
            activate: function (e, ui) {
                ui.oldTab.find("a")
                    .toggleClass("element-text-language-selected element-text-language");
                ui.newTab.find("a")
                    .toggleClass("element-text-language-selected element-text-language");
            }
        });

    var $entities = $(".tei-entities");
    var $infoPanel = $("#content-info");

    function showInPanel($elem) {
        if ($elem.length && $infoPanel.length) {
            var $clone = $elem.clone().css({display: "block"});
            $infoPanel
                .children()
                .remove()
                .end()
                .append($clone);
        }
    }

    $(".tei-entity-ref").hoverIntent(function () {
        var url = $(this).data("ref");
        showInPanel($entities.find(".content-info-entity[data-ref='" + url + "']").last());
    });

    $(".tei-note-ref").hoverIntent(function () {
        showInPanel($(this).next(".tei-note"));
    });


    // Disable pointer events on homepage maps until clicked
    $(".layout-neatline")
        .find("iframe").css({pointerEvents: "none"})
        .end()
        .click(function (e) {
            $(this).find("iframe").css({pointerEvents: "auto"});
        });

    // Menu
    var $body = $("body");

    // In desktop mode the visibility of the menu
    // is controlled by the element's corresponding
    // button is clicked, ensure that class is added
    // and other menu button's classes removed.
    $(".nav-bar-button").click(function (e) {
        e.preventDefault();
        var $this = $(this),
            cls = $this.data("class"),
            $target = $($this.data("target"));
        $body.toggleClass(cls);
        $(".nav-bar-button").not($this).each(function (i, elem) {
            $body.removeClass($(elem).data("class"));
        });
    });
    $(".nav-bar-back").click(function (e) {
        $(".nav-bar-button").each(function (i, elem) {
            $body.removeClass($(elem).data("class"));
        })
    });

    // Toggle the facet list expandion when the toggle is clicked.
    $("#nav-bar-limit-toggle").click(function (e) {
        var $this = $(this),
            $target = $($this.data("target")),
            active = $target.is(":visible");
        $this.toggleClass("collapse", active);
        $this.toggleClass("expand", !active);
        // .find("#nav-bar-limit-expand, #nav-bar-limit-shrink").toggle();
        // .text(active ? "keyboard_arrow_down" : "keyboard_arrow_up");
        $target.toggleClass("expand", !active);
    });

    // Comments
    if ($(".comment").length) {
        $("#comments-empty").hide(0);
    }


});