import web from 'massive-web';

/**
 * Service reference of request.
 */
web.registerService('request', {
    /**
     * Extracts parameter from url
     *
     * @param {String} sParam
     *
     * @return {String}
     */
    getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    }
});
