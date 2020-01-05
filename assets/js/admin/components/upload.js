import web from 'massive-web';
import $ from 'jquery';
import Dropzone from 'dropzone';
import 'bootstrap-sass';


/**
 * Upload component.
 */
class Upload {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     * @param {Object} options
     * @param {String} options.csrfToken
     * @param {Integer} options.folderId
     */
    initialize($el, options) {
        this.$el = $($el);
        this.$document = $(document);
        this.options = options;

        this.$modal = this.$document.find('.js-modal-upload');

        // Class.
        this.classDropzoneFrom = 'js-dropzone';

        this.bindListeners();
        this.onReady();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {  }

    /**
     * Ready.
     */
    onReady() {
        Dropzone.autoDiscover = false;
        let myDropzone = new Dropzone("." + this.classDropzoneFrom, {
            url: "/api/media/upload"
        });

        let totalFiles = 0;
        let completeFiles = 0;

        myDropzone.on('sending', (file, xhr, formData) => {
            formData.append('folderId', this.options.folderId);
            formData.append('token', this.options.csrfToken);
        });

        myDropzone.on('addedfile', (file) => {
            totalFiles += 1;
        });

        myDropzone.on("removed file", (file) => {
            totalFiles -= 1;
        });

        myDropzone.on("complete", (file) => {
            completeFiles += 1;

            if (completeFiles === totalFiles) {
                totalFiles = 0;
                completeFiles = 0;

                location.reload();
            }
        });
    }

    /**
     * Close modal.
     */
    closeModal() {
        this.$modal.modal('hide');
        this.$document.find('.modal-backdrop').last().remove(); // Fix bug.
    }
}

web.registerComponent('upload', Upload);