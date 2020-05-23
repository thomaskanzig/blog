import web from 'massive-web';
import $ from 'jquery';
import 'slick-carousel';

class HomepageHighlights {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     */
    initialize($el) {
        this.$el = $($el);
        this.$slider = this.$el.find('.js-homepage-highlights-slider');

        this.startSliderMobile();
    }

    /**
     * Starts slider.
     */
    startSliderMobile() {
        const $sliderDots = this.$el.find('.js-homepage-highlights-slider-dots');

        this.$slider.slick({
            slide: '.js-homepage-highlights-slide',
            slidesToShow: 1,
            slidesToScroll: 1,
            rows: 0, // Delete div tag by parent.
            dots: true,
            infinite: false,
            arrows: false,
            appendDots: $sliderDots,
        });
    }
}

web.registerComponent('homepage-highlights', HomepageHighlights);
