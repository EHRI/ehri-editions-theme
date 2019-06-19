jQuery(function($) {
  $("#document-text")
      .tabs({
        show: false,
        active: 0,
        activate: function(e, ui) {
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
      var $clone = $elem.clone().css({display:"block"});
      $infoPanel
          .children()
          .remove()
          .end()
          .append($clone);
    }
  }

  $(".tei-entity-ref").hoverIntent(function() {
    var url = $(this).data("ref");
    showInPanel($entities.find(".content-info-entity[data-ref='" + url + "']").last());
  });

  $(".tei-note-ref").hoverIntent(function() {
    showInPanel($(this).next(".tei-note"));
  });
});