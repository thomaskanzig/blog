import web from 'massive-web';
import $ from 'jquery';
import Masonry from 'masonry-layout';

class Gallery {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     */
    initialize($el) {
        this.$el = $($el);
        this.$window = $(window);
        this.$photos = this.$el.find('.js-gallery-photos-inner');

        this.bindListeners();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$window.on('load', this.initMasonry.bind(this));
    }

    /**
     * Initialize masonry after load window.
     */
    initMasonry() {
        var initMasonry = new Masonry(this.$photos.get(0), {
            itemSelector: '.js-gallery-photo-item',
        });
    }
}

web.registerComponent('gallery', Gallery);
