jQuery(document).ready( function( jQuery ){
  var _$ = jQuery; // jQuery

  // reset the explicitly defined height for all of our collapsible content divs
  function setCollapsibleContentHeights() {
    var collapsibleContents = _$('.rl-collapsible-section-content');
    _$.each(collapsibleContents, function(index, content) {
      _$(content).height( 'auto' );                    
      _$(content).height( _$(content).outerHeight() );
    });
  }

  var collapsibles = _$('.rl-collapsible-section');
  _$.each(collapsibles, function(index, collapsible) {
    var titleButton = _$(collapsible).find('.rl-collapsible-section-title button')[0];

    // toggle the content display when the title is clicked
    _$(titleButton).on('click', function() {
      _$(collapsible).toggleClass('rl-collapsed');
    });
  });

  // explicitly define the height of the content so css transitions work 
  // and recalculate content height when the window is resized
  setCollapsibleContentHeights();
  _$( window ).resize(function() {
    setCollapsibleContentHeights();
  });
});