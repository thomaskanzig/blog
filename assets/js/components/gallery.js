import web from 'massive-web';
import $ from 'jquery';
import Masonry from 'masonry-layout';
import shave from 'shave';
import moment from 'moment';
import { CSS_CLASS } from '../constans';

const MAX_HEIGHT_SHAVE = 35;

class Gallery {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     * @param {Object} options
     * @param {Boolean} options.showFBComments
     */
    initialize($el, options) {
        this.$el = $($el);
        this.$window = $(window);
        this.$document = $(document);
        this.$html = $('html');
        this.options = options;

        this.$photos = this.$el.find('.js-gallery-photos-inner');
        this.$photosDataTitle = this.$el.find('.js-gallery-photos-img-data-title');

        this.$galleryModal = this.$el.find('.js-gallery-modal');
        this.$galleryPhotoData = this.$el.find('.js-gallery-photo-data');
        this.$galleryModalTitle = this.$el.find('.js-gallery-modal-title');
        this.$galleryModalDesc = this.$el.find('.js-gallery-modal-description');
        this.$galleryModalPublished = this.$el.find('.js-gallery-modal-published');
        this.$galleryModalImg = this.$el.find('.js-gallery-modal-img');
        this.$galleryModalComments = this.$el.find('.js-gallery-modal-fb-comments');
        this.$galleryModalNavPrev = this.$el.find('.js-gallery-modal-nav-prev');
        this.$galleryModalNavNext = this.$el.find('.js-gallery-modal-nav-next');

        this.galleryModalCurrentPosition = 0;

        this.setShaveDataTitles();
        this.bindListeners();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$window.on('load', this.initMasonry.bind(this));
        this.$window.on('resize', this.setShaveDataTitles.bind(this));
        this.$document.on('keydown', this.onKeydown.bind(this));
        this.$galleryModal.on('show.bs.modal', this.showGalleryModal.bind(this));
        this.$galleryModal.on('hide.bs.modal', this.hideGalleryModal.bind(this));
        this.$galleryModalNavPrev.on('click', this.onPrevGalleryModal.bind(this));
        this.$galleryModalNavNext.on('click', this.onNextGalleryModal.bind(this));
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

    /**
     * Handle event by opened gallery modal.
     *
     * @param {Object} event
     */
    showGalleryModal(event) {
        let $image = $(event.relatedTarget);

        this.changeImageInGalleryModal($image);

        this.$html.addClass(CSS_CLASS.noOverflow);
    }

    /**
     * Handle event by close gallery modal.
     *
     * @param {Object} event
     */
    hideGalleryModal() {
        this.$html.removeClass(CSS_CLASS.noOverflow);
    }

    /**
     * Handle event to load image on modal.
     *
     * @param {Object} $image
     */
    changeImageInGalleryModal($image) {
        // Update position.
        this.galleryModalCurrentPosition = $image.data('position');

        // Update published.
        const datetime = moment($image.data('published'));
        this.$galleryModalPublished.html(datetime.format('MMMM Do [at] h:mm a'));

        // Update title.
        this.$galleryModalTitle.hide();
        if ($image.data('title')) {
            this.$galleryModalTitle.show().html($image.data('title'));
        }

        // Update desc.
        this.$galleryModalDesc.hide();
        if ($image.data('description')) {
            this.$galleryModalDesc.show().html($image.data('description'));
        }

        // Update image.
        this.$galleryModalImg.attr('src', $image.data('url'));

        if (this.options.showFBComments) {
            let fbComments = '<div class="fb-comments"' +
                'data-href="' + $image.data('url') + '"' +
                'data-width="100%"' +
                'data-numposts="20">' +
                '</div>';

            this.$galleryModalComments.html(fbComments);

            // To re-render social plugins with XFBML.
            // Re-render: https://developers.facebook.com/docs/reference/javascript/FB.XFBML.parse/
            // Comments: https://developers.facebook.com/docs/plugins/comments/
            FB.XFBML.parse(this.$galleryModalComments.get(0));
        }
    }

    /**
     * Click to previous image.
     */
    onPrevGalleryModal() {
        let prevPosition = (this.galleryModalCurrentPosition - 1);

        if (0 >= prevPosition) {
            prevPosition = this.$galleryPhotoData.length
        }

        this.changeImageInGalleryModal(this.$galleryPhotoData.filter(`[data-position="${prevPosition}"]`));
    }

    /**
     * Click to next image.
     */
    onNextGalleryModal() {
        let nextPosition = (this.galleryModalCurrentPosition + 1);

        if (this.$galleryPhotoData.length < nextPosition) {
            nextPosition = 1;
        }

        this.changeImageInGalleryModal(this.$galleryPhotoData.filter(`[data-position="${nextPosition}"]`));
    }

    /**
     * Click to change image on modal with keyboard (left/right).
     *
     * @param {Object} event
     */
    onKeydown(event) {
        const code = event.which;

        // Left.
        if (37 === code) {
            this.onPrevGalleryModal();
        // Right.
        } else if (39 === code) {
            this.onNextGalleryModal();
        }
    }
}

web.registerComponent('gallery', Gallery);
