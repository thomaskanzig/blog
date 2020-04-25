import web from 'massive-web';
import $ from 'jquery';
import Masonry from 'masonry-layout';
import shave from 'shave';

const MAX_HEIGHT_SHAVE = 35;

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
        this.$photosDataTitle = this.$el.find('.js-gallery-photos-img-data-title');

        this.setShaveDataTitles();
        this.bindListeners();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$window.on('load', this.initMasonry.bind(this));
        this.$window.on('resize', this.setShaveDataTitles.bind(this));
    }

    /**
     * Initialize masonry after load window.
     */
    initMasonry() {
        var initMasonry = new Masonry(this.$photos.get(0), {
            itemSelector: '.js-gallery-photo-item',
        });
    }

    /**
     * Set ellipsis text to the title preview photo.
     */
    setShaveDataTitles() {
        shave(this.$photosDataTitle, MAX_HEIGHT_SHAVE);
    }
}

web.registerComponent('gallery', Gallery);
