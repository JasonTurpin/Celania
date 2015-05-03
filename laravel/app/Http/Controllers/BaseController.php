<?php
namespace App\Http\Controllers;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\View;
use \Illuminate\Support\Facades\Route;
use \App\User, \App\Helper;


/**
 * Base Controller
 * File : /laravel/app/Http/Controllers/BaseController.php
 *
 * PHP version 5.3
 *
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */

/**
 * Base level controller
 *
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */
class BaseController extends Controller {
    /**
     * @var string - default header template
     */
    protected $header        = '';

    /**
     * @var string - default footer template
     */
    protected $footer        = '';

    /**
     * @var array - array of stylesheets to be loaded on the page
     */
    protected $stylesheets   = array();

    /**
     * @var array - array of JS files to be loaded
     */
    protected $scripts       = array();

    /**
     * @var array - array of error messages
     */
    protected $errorMsgs     = array();

    /**
     * @var array - Array of error field names
     */
    protected $errorFields   = array();

    /**
     * @var array - Array of user messages
     */
    protected $dashboardMessages = array();

    /**
     * @var User - Current user object
     */
    protected $user = null;

    /**
     * @var bool - if a login is required for the page
     */
    protected $loginRequired = true;

    // Theme level variables
    protected $adminThemeLoc;

    /**
     * Renders a template
     *
     * @param string $template Template name
     *
     * @return Response
     */
    protected function renderTemplate($template) {
        View::share('scripts', $this->scripts);
        View::share('stylesheets', $this->stylesheets);
        View::share('errorFields', $this->errorFields);
        View::share('_dashboardMessages', $this->dashboardMessages);
        View::share('_user', $this->user);

        return View::make($template);
    }

    /**
     * Include the CSS and JS necessary for a datatable
     *
     * @return void
     */
    protected function _includeDataTable() {
        // Add JS and CSS needed for data table
        $this->scripts[]     = $this->adminThemeLoc.'/assets/advanced-datatable/media/js/jquery.dataTables.js';
        $this->scripts[]     = $this->adminThemeLoc.'/assets/data-tables/DT_bootstrap.js';
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/data-tables/DT_bootstrap.css';
    }

    /**
     * Adds message to dashboard queue
     *
     * @param string $msg  Message to be displayed
     * @param string $type Message type (success or error)
     *
     * @return void
     */
    protected function addDashboardMessage($msg, $type = 'success') {
        $this->dashboardMessages[] = array(
            'msg'  => $msg,
            'type' => $type
        );
    }

    /**
     * Constructor Method
     */
    public function __construct() {
        // Admin theme location
        $this->adminThemeLoc = config('app.adminThemeLoc');

        // IF the user ID is in the session
        if (Session::has('_user_id')) {
            $this->user = User::find(Session::get('_user_id'));

        // Build a new User
        } else {
            $this->user = new User;
        }

        // Initialize contoller/action data
        $action = $controller = '';

        // Route name
        $controllerAction = strtolower(Route::currentRouteName());

        // Explode the action
        $pieces = explode('.', $controllerAction);

        // Set controller
        if (isset($pieces[0])) {
            $controller = $pieces[0];
        }

        // Set action
        if (isset($pieces[1])) {
            $action = $pieces[1];
        }

        // IF dashboard messages exist, display them
        if (Session::has('dashboardMessages')) {

            // Loop over each dashboard message
            foreach (Session::get('dashboardMessages') as $msg) {
                // IF message type was passed
                if (isset($msg['type'])) {
                    $this->addDashboardMessage($msg['msg'], $msg['type']);
                } else {
                    $this->addDashboardMessage($msg['msg']);
                }
            }
        }

        // Add CSS
        $this->stylesheets[] = '/css/style.css';

        // Add JS
        $this->scripts[]     = $this->adminThemeLoc.'/js/jquery.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/bootstrap.min.js';
        $this->scripts[]     = '/js/app.js';

        // Set view data
        View::share('errorFields', $this->errorFields);
        View::share('stylesheets', $this->stylesheets);
        View::share('scripts', $this->scripts);
        View::share('_keywords', config('app.keywords'));
        View::share('_description', config('app.description'));
        View::share('_siteURL', config('app.siteURL'));
        View::share('_title', config('app.titleTag'));
        View::share('_siteName', config('app.siteName'));
        View::share('_user', $this->user);
        View::share('_action', $action);
        View::share('_controller', $controller);
        View::share('_controllerAction', $controllerAction);
        View::share('_dashboardMessages', $this->dashboardMessages);
    }

    /**
     * Add JS/CSS for file upload
     *
     * @return void
     */
    protected function _useFileUpload() {
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/bootstrap-fileupload/bootstrap-fileupload.css';
        $this->scripts[]     = $this->adminThemeLoc.'/assets/bootstrap-fileupload/bootstrap-fileupload.js';
    }

    /**
     * Use searchable multi-select box
     *
     * @return void
     */
    protected function _useSearchableMultiSelect() {
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/bootstrap-fileupload/bootstrap-fileupload.css';
        $this->scripts[]     = $this->adminThemeLoc.'/jquery-multi-select/js/jquery.multi-select.js';
        $this->scripts[]     = $this->adminThemeLoc.'//jquery-multi-select/js/jquery.quicksearch.js';
    }

    /**
     * Use jQuery UI code
     *
     * @return void
     */
    protected function _useJQueryUI() {
        $this->scripts[]     = $this->adminThemeLoc.'/assets/jquery-ui/jquery-ui-1.10.1.custom.min.js';
        $this->stylesheets[] = '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css';
    }

    /**
     * Use JS Stepy Code
     *
     * @return void
     */
    protected function _useStepy() {
        // Add stepy JS code
        $this->scripts[] = $this->adminThemeLoc.'/js/jquery.stepy.js';
    }

    /**
     * Use Colorpicker Code
     *
     * @return void
     */
    protected function _useColorPicker() {
        $this->scripts[]     = '/packages/colorMaster/tinycolor-0.9.15.min.js';
        $this->scripts[]     = '/packages/colorMaster/pick-a-color-1.2.3.min.js';
        $this->stylesheets[] = '/packages/colorMaster/pick-a-color-1.2.3.min.css';
    }

    /**
     * Unsets all $_SESSION variables
     *
     * @return void
     */
    protected function _resetSession() {
        Session::forget('_user_id');
        Session::forget('_username');
        Session::forget('_loginHash');
        Session::forget('_roles');
    }

    /**
     * Default 403 handler
     *
     * @return View
     */
    public function do_403() {
        // Add stylesheets to load
        $this->stylesheets[] = $this->adminThemeLoc.'/css/bootstrap.min.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/bootstrap-reset.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/font-awesome/css/font-awesome.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/style.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/style-responsive.css';
        View::share('stylesheets', $this->stylesheets);

        return View::make('pages.Home403');
    }

    /**
     * Default 404 handler
     *
     * @return View
     */
    public function do_404() {
        // Add stylesheets to load
        $this->stylesheets[] = $this->adminThemeLoc.'/css/bootstrap.min.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/bootstrap-reset.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/font-awesome/css/font-awesome.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/style.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/style-responsive.css';
        View::share('stylesheets', $this->stylesheets);

        return View::make('pages.Home404');
    }

    /**
     * Add calendar data
     *
     * @return void
     */
    protected function _useCalendar() {
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/bootstrap-timepicker/compiled/timepicker.css';
        $this->scripts[]     = $this->adminThemeLoc.'/js/jquery-ui-1.9.2.custom.min.js';
        $this->scripts[]     = $this->adminThemeLoc.'/assets/fullcalendar/fullcalendar/fullcalendar.min.js';
        $this->scripts[]     = $this->adminThemeLoc.'/assets/bootstrap-timepicker/js/bootstrap-timepicker.js';
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }
}
