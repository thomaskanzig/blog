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

        this.$selectTemplate = this.$el.find('.js-post-template');
        this.$gallery = this.$el.find('.js-post-gallery-content');
        this.$galleryModalContent = this.$el.find('.js-modal-post-gallery-content');
        this.$galleryAdd = this.$el.find('.js-post-gallery-add');
        this.$galleryMediaFile = this.$el.find('.js-modal-post-gallery-file');

        this.bindListeners();
        this.onReady();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$selectTemplate.on('change', this.onChangeTemplate.bind(this));
        this.$galleryAdd.on('click', this.onOpenModalGallery.bind(this));
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
                console.log(data.files);

                if(0 === data.files.length){
                    return;
                }

                $.each(data.files, (index, media) => {
                    // Only images.
                    if('image' === media.type.slug){
                        let $file = this.$galleryMediaFile.clone().removeClass(CSS_CLASS.isCopy);
                        $file.find('.js-modal-post-gallery-file-img').attr('src', '/' + media.file);

                        this.$galleryModalContent.append($file);
                    }
                });
            }
        });
    }
}

web.registerComponent('post', Post);
