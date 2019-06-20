jQuery(function ($) {

  /**
   * If the button's target is visible, set it to
   * be selected, set it's icon to a 'X', and ensure
   * the target's corresponding body class is on the
   * body element
   *
   * @param elem the button element
   */
  function resetMenuButtonState(elem) {
    var $item = $(elem),
        $icon = $item.find(".material-icons"),
        active = $($item.data("target")).is(":visible");
    $item.toggleClass("selected", active);
    $icon.text(active ? "close" : $icon.data("text"));
    $("body").toggleClass($item.data("class"), active);
  }

  var mobileWidth = 930,
      $body = $("body");

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
    })
  });
  $(".nav-bar-back").click(function (e) {
    $(".nav-bar-button").each(function (i, elem) {
      $body.removeClass($(elem).data("class"));
    })
  });

  // Set mobile menu state according to body class...
  $(".nav-bar-mobile-button").each(function (i, elem) {
    resetMenuButtonState(elem);
  }).click(function (e) {
    e.preventDefault();
    var $this = $(this);
    $($this.data("target")).toggle(!$this.hasClass("selected"));
    resetMenuButtonState(this);

    // Close other menu(s)...
    $(".nav-bar-mobile-button").not($this).each(function (i, elem) {
      $($(elem).data("target")).hide();
      resetMenuButtonState(elem);
    });
  });

  // Toggle the facet list expandion when the toggle is clicked.
  $("#nav-bar-limit-toggle").click(function (e) {
    var $this = $(this),
        $target = $($this.data("target")),
        active = $target.is(":visible");
    $this
        .find("#nav-bar-limit-expand")
        .text(active ? "keyboard_arrow_down" : "keyboard_arrow_up");
    $target.toggle(!active);
  });

  // In the unlikely event of resizing the mobile page, reset
  // visibility of some elements that may have been set by JS
  $(window).resize(function () {
    if (window.outerWidth > mobileWidth) {
      $(".nav-bar").removeAttr("style");
      $("#nav-bar-limit-search").removeAttr("style");
    } else {
      $(".nav-bar-mobile-button").each(function (i, elem) {
        resetMenuButtonState(elem);
      });
    }
  });
});
