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
        this.$window = $(window);
        this.$document = $(document);
        this.$html = $('html');

        this.$items = this.$el.find('.js-highlights-item');
        this.$slider = this.$el.find('.js-homepage-highlights-slider');

        this.startSliderMobile();
        this.bindListeners();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$window.on('load', this.onLoad.bind(this));
        this.$window.on('resize', this.onResize.bind(this));
    }

    /**
     * Initialize features after load JS.
     */
    onLoad() {

    }

    /**
     * Handle resize window.
     */
    onResize() {

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
