jQuery(document).ready( function( jQuery ){
  var _$ = jQuery; // jQuery

  var collapsibles = _$('.rl-collapsible-section');

  _$.each(collapsibles, function(index, collapsible) {
    _$collapsible = _$(collapsible);
    // toggle the content display when the title is clicked
    var titleButton = _$collapsible.find('.rl-collapsible-section-title button')[0];

    _$(titleButton).on('click', function() {
      _$(this).closest('.rl-collapsible-section').toggleClass('rl-collapsed');

      // toggle the aria-expanded attribute on the button
      _$(this).attr('aria-expanded', function (i, attr) {
        return attr == 'true' ? 'false' : 'true'
      });
    });
  });

  // Automatically expand a collapsible when internally linking to 
  // an element within it.
  _$('a').on('click', function(e) {
    var windowUri = window.location.href.split("#")[0];
    var linkUri = this.href.split('#')[0];
    
    if ( linkUri.indexOf( windowUri ) > -1 ) {
      var collapsible =  _$(this.hash).closest('.rl-collapsible-section');
      expandCollapsible(collapsible);
    }
  });

  function expandCollapsible(collapsible) {
    _$collapsible = _$(collapsible);
    if ( _$collapsible.hasClass('rl-collapsed') ) {
      _$collapsible.removeClass('rl-collapsed');
      var titleButton = _$collapsible.find('.rl-collapsible-section-title button')[0];
      _$(titleButton).attr('aria-expanded', 'true');
    }
  }

  function collapseCollapsible(collapsible) {
    _$collapsible = _$(collapsible);
    if ( !_$collapsible.hasClass('rl-collapsed') ) {
      _$collapsible.addClass('rl-collapsed');
      var titleButton = _$collapsible.find('.rl-collapsible-section-title button')[0];
      _$(titleButton).attr('aria-expanded', 'false');
    }
  }
  
  _$('.rl-collapsible-section-toggle-button').on('click', function() {
    var expanded = _$(this).data('toggle-expanded');
    if (expanded) {
      // collapse all sections
      _$('.rl-collapsible-section').each(function() {
        collapseCollapsible(this);
      });
    } else {
      // expand all sections
      _$('.rl-collapsible-section').each(function() {
        expandCollapsible(this);
      });
    }

    // toggle the data attribute on all toggle buttons
    _$('.rl-collapsible-section-toggle-button').each(function() {
      var expanded = _$(this).data('toggle-expanded');
      _$(this).data('toggle-expanded', !expanded);
    });
  });
});