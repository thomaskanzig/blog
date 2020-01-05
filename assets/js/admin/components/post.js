import web from 'massive-web';
import $ from 'jquery';
import 'bootstrap-sass';


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

        this.classIsShow = 'is-show';

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
            this.$gallery.addClass(this.classIsShow);
        } else {
            this.$gallery.removeClass(this.classIsShow);
        }
    }

    /**
     * Open modal to add/delete images of gallery.
     */
    onOpenModalGallery() {
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
                console.log(data);
            }
        });
    }
}

web.registerComponent('post', Post);
