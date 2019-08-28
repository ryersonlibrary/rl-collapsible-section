jQuery(document).ready( function( jQuery ){
  var _$ = jQuery; // jQuery

  var collapsibles = _$('.rl-collapsible-section');

  _$.each(collapsibles, function(index, collapsible) {
    console.log(collapsible);

    _$collapsible = _$(collapsible);
    // toggle the content display when the title is clicked
    var titleButton = _$collapsible.find('.rl-collapsible-section-title button')[0];

    _$(titleButton).on('click', function() {
      _$(this).closest('.rl-collapsible-section').toggleClass('rl-collapsed');
    });
  });
});