import web from 'massive-web';
import $ from 'jquery';
import 'bootstrap-sass';
import { CSS_CLASS } from '../../constans';

/**
 * Post component.
 */
class Post {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     * @param {Object} options
     * @param {String} options.csrfTokenMedia
     */
    initialize($el, options) {
        this.$el = $($el);
        this.options = options;

        // Gallery modal.
        this.$gallery = this.$el.find('.js-post-gallery-content');
        this.$galleryModalContent = this.$el.find('.js-modal-post-gallery-content');
        this.$galleryMediaFile = this.$el.find('.js-modal-post-gallery-file');
        this.$galleryModalInputImages = this.$el.find('.js-modal-post-gallery-file-checkbox');

        // Gallery selected.
        this.$galleryAdd = this.$el.find('.js-post-gallery-add');
        this.$selectTemplate = this.$el.find('.js-post-template');
        this.$galleryInputImages = this.$el.find('.js-input-gallery-images');
        this.$galleryImagesSelected = this.$el.find('.js-post-gallery-images-selected');
        this.$gallerySelectedFileCopy = this.$el.find('.js-post-gallery-images-selected-file').filter('.' + CSS_CLASS.isCopy);

        this.bindListeners();
        this.onReady();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$selectTemplate.on('change', this.onChangeTemplate.bind(this));
        this.$galleryAdd.on('click', this.onOpenModalGallery.bind(this));
        this.$galleryModalInputImages.on('click', this.onChangeGalleryModalInputImages.bind(this));
    }

    /**
     * Ready.
     */
    onReady() { }

    /**
     * If change template then other fields should appear in form.
     */
    onChangeTemplate(e){
        const $selectTemplate = $(e.currentTarget);

        // If gallery
        if(2 === parseInt($selectTemplate.val())){
            this.$gallery.addClass(CSS_CLASS.isShow);
        } else {
            this.$gallery.removeClass(CSS_CLASS.isShow);
        }
    }

    /**
     * Open modal to add/delete images of gallery.
     */
    onOpenModalGallery() {
        // Delete all files inside modal, if exist.
        this.$galleryModalContent
            .find('.js-modal-post-gallery-file:not(.' + CSS_CLASS.isCopy + ')')
            .remove();

        let objData = {
            'page': 1,
            'token': this.options.csrfTokenMedia
        };

        // Get all images selected in form.
        const selected = this.getSelectedImagesForm();

        $.ajax({
            url: '/api/media/list',
            data: objData,
            type: 'GET',
            contentType: 'application/x-www-form-urlencoded',
            error: (data) => {
                /* eslint-disable */
                console.error(data);
                /* eslint-enable */
            },
            success: (data) => {
                // console.log(data.files);

                if(0 === data.files.length){
                    return;
                }

                $.each(data.files, (index, media) => {
                    // Only images.
                    if('image' === media.type.slug) {
                        let $file = this.$galleryMediaFile.clone(true).removeClass(CSS_CLASS.isCopy);

                        $file.find('.js-modal-post-gallery-file-img').attr('src', '/' + media.file);
                        $file.find('.js-modal-post-gallery-file-checkbox').attr('id', 'gallery-modal-image-' + media.id);
                        $file.find('.js-modal-post-gallery-file-checkbox').val(media.id);
                        $file.find('.js-modal-post-gallery-file-checkbox').attr('data-media-file', '/' + media.file);

                        if (-1 < selected.indexOf(String(media.id))) {
                            $file.find('.js-modal-post-gallery-file-checkbox').prop('checked', true);
                        }

                        $file.find('.js-modal-post-gallery-file-label').attr('for', 'gallery-modal-image-' + media.id);

                        this.$galleryModalContent.append($file);
                    }
                });
            }
        });
    }

    /**
     * Updated content selected images from gallery.
     */
    updateGallerySelected() {
        console.log('occurred an click');
    }

    /**
     * Handle change input from gallery modal.
     */
    onChangeGalleryModalInputImages(e) {
        const $checkbox = $(e.currentTarget);
        let selected = this.getSelectedImagesForm();

        if($checkbox.is(':checked')) {
            selected.push($checkbox.val());
            let $file = this.$gallerySelectedFileCopy.clone(true).removeClass(CSS_CLASS.isCopy);
            $file.attr('data-media-id', $checkbox.val());
            $file.find('.js-post-gallery-images-selected-file-img').attr('src', $checkbox.data('media-file'));
            this.$galleryImagesSelected.append($file);
        } else {
            selected.splice(selected.indexOf($checkbox.val()), 1);
            this.$galleryImagesSelected.find('[data-media-id=' + $checkbox.val() + ']').remove();
        }

        // Update checkbox in form.
        let selectedJSON = JSON.stringify(selected);
        this.$galleryInputImages.val(selectedJSON);
    }

    getSelectedImagesForm() {
        let selected = this.$galleryInputImages.val();

        if(!selected) {
            selected = [];
        } else {
            selected = JSON.parse(selected);
        }

        return selected;
    }
}

web.registerComponent('post', Post);
