import web from 'massive-web';
import $ from 'jquery';
import 'bootstrap-sass';
import { CSS_CLASS } from '../../constans';
import { HTML } from '../../constans';

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
     * @param {String} options.medias
     */
    initialize($el, options) {
        this.$el = $($el);
        this.options = options;

        // Gallery modal.
        this.$gallery = this.$el.find('.js-post-gallery-content');
        this.$galleryModalContent = this.$el.find('.js-modal-post-gallery-content');
        this.$galleryMediaFile = this.$el.find('.js-modal-post-gallery-file');
        this.$galleryModalInputImages = this.$el.find('.js-modal-post-gallery-file-checkbox');
        this.$galleryModalBody = this.$el.find('.js-modal-body-post-gallery');
        this.$galleryModalLoading = this.$el.find('.js-modal-body-post-gallery-loading');
        this.$galleryModalLoading.html(HTML.loaderSpinner);

        // Gallery selected.
        this.$galleryAdd = this.$el.find('.js-post-gallery-add');
        this.$selectTemplate = this.$el.find('.js-post-template');
        this.$galleryInputImages = this.$el.find('.js-input-gallery-images');
        this.$galleryImagesSelected = this.$el.find('.js-post-gallery-images-selected');
        this.$gallerySelectedFileCopy = this.$el.find('.js-post-gallery-images-selected-file').filter('.' + CSS_CLASS.isCopy);
        this.$gallerySelectedFileDelete = this.$el.find('.js-post-gallery-images-selected-file-delete');

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
        this.$gallerySelectedFileDelete.on('click', this.onClickGallerySelectedFileDelete.bind(this));
        this.$galleryModalBody.on('scroll', this.onScrollGalleryModalBody.bind(this));
    }

    /**
     * Ready.
     */
    onReady() {
        if (this.options.medias) {
            this.insertSelectedImagesForm(this.options.medias);
        }
    }

    /**
     * If change template then other fields should appear in form.
     *
     * @param {Object} e
     */
    onChangeTemplate(e) {
        const $selectTemplate = $(e.currentTarget);

        // If template is a gallery.
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

        this.$galleryModalLoading.addClass(CSS_CLASS.isVisible);
        this.galleryModalNextPage = 1;
        this.galleryModalPagination = [];

        this.loadlMediaFiles();
    }

    /**
     * Load media files via API.
     */
    loadlMediaFiles() {
        // If next page is false, then will not load more files.
        if (!this.galleryModalNextPage) {
            this.$galleryModalLoading.removeClass(CSS_CLASS.isVisible);
            return;
        }

        this.$galleryModalLoading.addClass(CSS_CLASS.isVisible);

        if (this.galleryModalPagination) {
            this.galleryModalNextPage = this.galleryModalPagination.next;
        }

        let objData = {
            'type': 'image',
            'page': this.galleryModalNextPage,
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
                this.$galleryModalLoading.removeClass(CSS_CLASS.isVisible);

                /* eslint-disable */
                console.error(data);
                /* eslint-enable */
            },
            success: (data) => {
                if(0 === data.files.length){
                    return;
                }

                this.galleryModalPagination = data.pagination;
                if (typeof this.galleryModalPagination.next != "undefined") {
                    this.galleryModalNextPage = this.galleryModalPagination.next;
                } else {
                    this.galleryModalNextPage = false;
                    this.$galleryModalLoading.removeClass(CSS_CLASS.isVisible);
                }

                $.each(data.files, (index, media) => {
                    const image = {
                        id: media.id,
                        file: media.file
                    };

                    this.updateModalGallery(image, selected);
                });
            }
        });
    }

    /**
     * Updated content selected images from gallery.
     *
     * @param {Object} image
     * @param {Object} selected
     */
    updateModalGallery(image, selected) {
        let $file = this.$galleryMediaFile.clone(true).removeClass(CSS_CLASS.isCopy);

        $file.find('.js-modal-post-gallery-file-img').attr('src', image.file);
        $file.find('.js-modal-post-gallery-file-checkbox').attr('id', 'gallery-modal-image-' + image.id);
        $file.find('.js-modal-post-gallery-file-checkbox').val(image.id);
        $file.find('.js-modal-post-gallery-file-checkbox').attr('data-media-file', image.file);

        if (-1 < selected.indexOf(String(image.id))) {
            $file.find('.js-modal-post-gallery-file-checkbox').prop('checked', true);
        }

        $file.find('.js-modal-post-gallery-file-label').attr('for', 'gallery-modal-image-' + image.id);

        this.$galleryModalContent.append($file);
    }

    /**
     * Handle change input from gallery modal.
     *
     * @param {Object} e
     */
    onChangeGalleryModalInputImages(e) {
        const $checkbox = $(e.currentTarget);

        if($checkbox.is(':checked')) {
            const medias = {
                id: String($checkbox.val()),
                file: $checkbox.data('media-file')
            };

            this.insertSelectedImagesForm([medias]);
        } else {
            this.deleteSelectedImagesForm([$checkbox.val()]);
        }
    }

    /**
     * Show selected medias from actually post and insert a new media (ids) in input form.
     *
     * @param {Object} medias
     */
    insertSelectedImagesForm(medias) {
        let selected = this.getSelectedImagesForm();

        $.each(medias, (index, media) => {
            // Added media to input hide.
            selected.push(String(media.id));

            // Added media to html.
            let $file = this.$gallerySelectedFileCopy.clone(true).removeClass(CSS_CLASS.isCopy);
            $file.attr('data-media-id', media.id);
            $file.find('.js-post-gallery-images-selected-file-img').attr('src', media.file);
            this.$galleryImagesSelected.append($file);

            // Update checkbox in form.
            let selectedJSON = JSON.stringify(selected);
            this.$galleryInputImages.val(selectedJSON);
        });
    }

    /**
     * Get value images (media id) selected in form.
     *
     * @return {Object}
     */
    getSelectedImagesForm() {
        let selected = this.$galleryInputImages.val();

        if (!selected) {
            selected = [];
        } else {
            selected = JSON.parse(selected);
        }

        return selected;
    }

    /**
     * Delete file in input form.
     *
     * @param {Object} values
     */
    deleteSelectedImagesForm(values) {
        let selected = this.getSelectedImagesForm();

        $.each(values, (index, mediaId) => {
            selected.splice( selected.indexOf(String(mediaId)) , 1);

            this.$galleryImagesSelected.find('[data-media-id=' + mediaId + ']').remove();

            // Update checkbox in form.
            let selectedJSON = JSON.stringify(selected);
            this.$galleryInputImages.val(selectedJSON);
        });
    }

    /**
     * Delete file.
     *
     * @param {Object} e
     */
    onClickGallerySelectedFileDelete(e) {
        let $file = $(e.currentTarget).closest('.js-post-gallery-images-selected-file');
        const mediaId = $file.data('media-id');

        this.deleteSelectedImagesForm([mediaId]);
    }

    /**
     * Load more media files by scrolling in modal gallery.
     *
     * @param {Object} e
     */
    onScrollGalleryModalBody(e) {
        const $element = $(e.currentTarget);

        // Identify if scrolling is finish.
        if(($element.scrollTop() + $element.innerHeight()) >= $element[0].scrollHeight) {
            setTimeout(() => {
                this.loadlMediaFiles();
            }, 1000);
        }
    }
}

web.registerComponent('post', Post);
