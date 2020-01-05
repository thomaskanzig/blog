import web from 'massive-web';
import $ from 'jquery';

/**
 * Clipboard component.
 */
class Clipboard {
    /**
     * Initialize component.
     *
     * @param {Object} $el
     */
    initialize($el) {
        this.$el = $($el);
        this.$btnText = this.$el.find('.js-text-clipboard');

        this.bindListeners();
    }

    /**
     * Binds event listeners.
     */
    bindListeners() {
        this.$el.on('click', this.copyText.bind(this));
    }

    /**
     * Copy text.
     */
    copyText() {
        // Create new element
        let el = document.createElement('textarea');

        // Set value (string to be copied)
        el.value = this.$el.data('text-copy');
        document.body.appendChild(el);

        // Select text inside element
        el.select();

        // Copy text to clipboard
        document.execCommand('copy');

        // Remove temporary element
        document.body.removeChild(el);

        let text = this.$btnText.text();
        this.$btnText.text('Copied!');

        setTimeout(() => {
            this.$btnText.text(text);
        }, 1000);
    }
}

web.registerComponent('clipboard', Clipboard);