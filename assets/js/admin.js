/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/admin.scss');

// Import JS from AdminLTE.
require('admin-lte/plugins/iCheck/icheck');
require('admin-lte/bower_components/jquery-slimscroll/jquery.slimscroll');
require('admin-lte/bower_components/fastclick/lib/fastclick.js');
require('admin-lte/dist/js/adminlte.min');

// Import components.
import './components/clipboard';
import './components/dotdotdot';
import './admin/components/upload';
import './admin/components/media';
import './admin/components/post';
