jQuery(function ($) {
    $("#document-text")
        .tabs({
            show: false,
            active: 0,
            activate: function (e, ui) {
                ui.oldTab.find("a").removeClass("selected");
                ui.newTab.find("a").addClass("selected");
            }
        });

    var $entities = $(".tei-entities");
    var $infoPanel = $("#content-info");

    function showInPanel($elem, ref) {
        if ($(window).width() < 720) {
            console.log("Pop", $elem.prop('outerHTML'));
            // FIXME: mobile popup...
            // $(ref).popover({
            //     content: $elem.get(0).outerHTML,
            //     html: true
            // }).popover('show');
        } else {
            if ($elem.length && $infoPanel.length) {
                var $clone = $elem.clone().css({display: "block"});
                $infoPanel
                    .children()
                    .remove()
                    .end()
                    .append($clone);
            }
        }
    }

    // Close popovers on mouseup...
    $("html").on("mouseup", function (e) {
        var l = $(e.target);
        if (l[0].className.indexOf("popover") === -1) {
            $(".popover").each(function () {
                $(this).popover("hide");
            });
        }
    });

    $(".tei-entity-ref").hoverIntent(function () {
        var url = $(this).data("ref");
        var $elem = $entities.find(".content-info-entity[data-ref='" + url + "']").last();
        showInPanel($elem, this);
    });

    $(".tei-note-ref").hoverIntent(function () {
        var $elem = $(this).next(".tei-note");
        showInPanel($elem, this);
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