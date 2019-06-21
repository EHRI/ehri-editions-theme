jQuery(function ($) {

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
    })
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
});
