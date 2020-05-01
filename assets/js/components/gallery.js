import web from 'massive-web';
import $ from 'jquery';
import Masonry from 'masonry-layout';
import shave from 'shave';
import moment from 'moment';
import { CSS_CLASS } from '../constans';
import { BREAKPOINTS } from '../constans';
import { HTML } from '../constans';

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
        this.$location = $(location);
        this.$html = $('html');
        this.options = options;
        this.request = web.getService('request');

        this.$photos = this.$el.find('.js-gallery-photos-inner');
        this.$photosDataTitle = this.$el.find('.js-gallery-photos-img-data-title');

        this.$galleryModal = this.$el.find('.js-gallery-modal');
        this.$galleryModalRight = this.$el.find('.js-gallery-modal-right');
        this.$galleryModalRightInner = this.$el.find('.js-gallery-modal-right-inner');
        this.$galleryModalMain = this.$el.find('.js-gallery-modal-main');
        this.$galleryPhotoData = this.$el.find('.js-gallery-photo-data');
        this.$galleryModalTitle = this.$el.find('.js-gallery-modal-title');
        this.$galleryModalDesc = this.$el.find('.js-gallery-modal-description');
        this.$galleryModalPublished = this.$el.find('.js-gallery-modal-published');
        this.$galleryModalImg = this.$el.find('.js-gallery-modal-img');
        this.$galleryModalComments = this.$el.find('.js-gallery-modal-fb-comments');
        this.$galleryModalAuthorName = this.$el.find('.js-gallery-modal-author-name');
        this.$galleryModalAuthorAvatar = this.$el.find('.js-gallery-modal-author-avatar');
        this.$galleryModalNavPrev = this.$el.find('.js-gallery-modal-nav-prev');
        this.$galleryModalNavNext = this.$el.find('.js-gallery-modal-nav-next');
        this.$galleryModalArrowToggler = this.$el.find('.js-gallery-modal-main-arrow-toggler');
        this.$galleryModalPaging = this.$el.find('.js-gallery-modal-paging');

        // Set total of images on paging.
        this.$galleryModalPaging.find('.js-gallery-modal-paging-total').html(this.$galleryPhotoData.length);

        this.galleryModalCurrentPosition = 0;

        this.setShaveDataTitles();
        this.bindListeners();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$window.on('load', this.onLoad.bind(this));
        this.$window.on('resize', this.onResize.bind(this));
        this.$document.on('keydown', this.onKeydown.bind(this));
        this.$galleryModal.on('show.bs.modal', this.showGalleryModal.bind(this));
        this.$galleryModal.on('hide.bs.modal', this.hideGalleryModal.bind(this));
        this.$galleryModalNavPrev.on('click', this.onPrevGalleryModal.bind(this));
        this.$galleryModalNavNext.on('click', this.onNextGalleryModal.bind(this));
        this.$galleryModalArrowToggler.on('click', this.onTogglerGalleryModalDetail.bind(this));
    }

    /**
     * Initialize features after load JS.
     */
    onLoad() {
        var initMasonry = new Masonry(this.$photos.get(0), {
            itemSelector: '.js-gallery-photo-item',
        });

        if (0 < this.getDataImageFromParameter().length) {
            setTimeout(() => {
                this.$galleryModal.modal('show');
            }, 1000);
        }
    }

    /**
     * Handle resize window.
     */
    onResize() {
        this.setShaveDataTitles();

        // Reset custom style.
        this.$galleryModalRight.removeAttr('style');
        this.$galleryModalRightInner.removeAttr('style');
        this.$galleryModalArrowToggler.removeAttr('style');

        // Everything was open, will close.
        this.$galleryModalRight.removeClass(CSS_CLASS.isShow);
        this.$galleryModalArrowToggler.removeClass(CSS_CLASS.isShow);
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

        // Target click from image.
        if(0 < $image.length) {
            this.changeImageInGalleryModal($image);
            this.$html.addClass(CSS_CLASS.noOverflow);

            return;
        }

        // Target image from url.
        $image = this.getDataImageFromParameter();

        if(0 < $image.length) {
            this.changeImageInGalleryModal($image);
            this.$html.addClass(CSS_CLASS.noOverflow);
        }
    }

    /**
     * Handle event by close gallery modal.
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

        // Update author name.
        this.$galleryModalAuthorName.hide();
        if ($image.data('author-name')) {
            this.$galleryModalAuthorName.show().html($image.data('author-name'));
        }

        // Update author avatar.
        this.$galleryModalAuthorAvatar.attr('src','');
        if ($image.data('author-avatar')) {
            this.$galleryModalAuthorAvatar.attr('src', $image.data('author-avatar'));
        }

        // Update image.
        this.$galleryModalImg.attr('src', $image.data('url'));

        // Update facebook comments.
        this.$galleryModalComments.hide().html('');

        if (this.options.showFBComments) {
            try {
                const urlImage = this.$location.attr('origin') + this.$location.attr('pathname') + '?image=' + $image.data('id');

                let fbComments = '<div class="fb-comments"' +
                    'data-href="' + urlImage + '"' +
                    'data-width="100%"' +
                    'data-numposts="20">' +
                    '</div>';

                this.$galleryModalComments.show().html(fbComments);

                // To re-render social plugins with XFBML.
                // Re-render: https://developers.facebook.com/docs/reference/javascript/FB.XFBML.parse/
                // Comments: https://developers.facebook.com/docs/plugins/comments/
                FB.XFBML.parse(this.$galleryModalComments.get(0));
            } catch(err) {
                console.error(err);
            }
        }

        this.updateModalGalleryPaging();
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

    onTogglerGalleryModalDetail() {
        if (this.$window.width() <= BREAKPOINTS.xs) {
            // If not show yet, then will show.
            if (!this.$galleryModalRight.hasClass(CSS_CLASS.isShow)) {
                const width = this.$galleryModalMain.width();

                this.$galleryModalRight.css({
                    'width': width + 'px',
                    'right': '0'
                });

                this.$galleryModalRightInner.css('width',  width + 'px');
                this.$galleryModalArrowToggler.css('right', width - 50 + 'px');
            } else {
                // Reset custom style.
                this.$galleryModalRight.removeAttr('style');
                this.$galleryModalRightInner.removeAttr('style');
                this.$galleryModalArrowToggler.removeAttr('style');
            }
        }

        this.$galleryModalRight.toggleClass(CSS_CLASS.isShow);
        this.$galleryModalArrowToggler.toggleClass(CSS_CLASS.isShow);
    }

    /**
     * Get image data object through image id parameter.
     *
     * @return {Object}
     */
    getDataImageFromParameter() {
        const imageId = this.request.getUrlParameter('image');
        return this.$galleryPhotoData.filter(`[data-id="${imageId}"]`);
    }

    /**
     * Update paging of gallery modal.
     */
    updateModalGalleryPaging() {
        this.$galleryModalPaging.find('.js-gallery-modal-paging-current').html(this.galleryModalCurrentPosition);
    }
}

web.registerComponent('gallery', Gallery);
