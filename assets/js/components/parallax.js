import web from 'massive-web';
import $ from 'jquery';
import 'simple-parallax-jquery';

/**
 * Parallax component.
 */
class Parallax {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     */
    initialize($el) {
        this.$el = $($el);

        this.$el.simpleParallax({
            delay: 0,
            scale: 1.5,
            overflow: true,
        });
    }
}

web.registerComponent('parallax', Parallax);
