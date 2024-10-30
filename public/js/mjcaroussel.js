/*
* Name Style: mjcaroussel.js
* v1.0.0 (http://www.morgan-jourdin.fr/)
* Copyright Morgan JOURDIN.
*
*/

var mjCaroussel = function() {
    this.class = 'mjcaroussel';
    this.init();
};

mjCaroussel.prototype = {
  init: function() {
    var self = this;

    self.slickInit();
  },

  slickInit: function() {
    jQuery( 'div.' + this.class ).slick({
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1
    });
  }
}

mj = new mjCaroussel();
