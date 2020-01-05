import web from 'massive-web';
import $ from 'jquery';
import 'jquery.dotdotdot';

class Dotdotdot {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     */
    initialize($el) {
        this.$textContainer = $($el);

        this.initDotDotDot();
    }

    /**
     * Initialize dotdotdot plugin on the wanted elements.
     */
    initDotDotDot() {
        this.$textContainer.dotdotdot();
    }
}

web.registerComponent('dotdotdot', Dotdotdot);
