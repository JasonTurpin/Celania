<?php
namespace App\Http\Controllers;
use \Illuminate\Support\Facades\View;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Session;
use \App\User, \App\Role, \App\Helper;

/**
 * Admin Controller - Base class for all admin screens
 * File : /laravel/app/Http/Controllers/AdminController.php
 *
 * PHP version 5.3
 *
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */

/**
 * Handles Dealer Level Screens
 *
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */
class AdminController extends BaseController {
    /**
     * Constructor Method
     */
    public function __construct() {
        // Run parent constructor
        parent::__construct();

        // Ensure user is logged in
        $this->beforeFilter(function() {
            // IF the user is requesting the sign in screen
            if (Route::currentRouteName() == 'admin.signin') {
                return;

            // IF the user is not logged in
            } elseif (empty($this->user->user_id)) {
                return redirect()->route('admin.signin');

            // IF the user does not have access to the current role
            } elseif (!$this->user->roleTest('Admin')) {
                return redirect()->route('404');
            }
        });

        // Add stylesheets to load
        $this->stylesheets[] = $this->adminThemeLoc.'/css/bootstrap.min.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/bootstrap-reset.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/font-awesome/css/font-awesome.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/assets/gritter/css/jquery.gritter.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/slidebars.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/style.css';
        $this->stylesheets[] = $this->adminThemeLoc.'/css/style-responsive.css';
        View::share('stylesheets', $this->stylesheets);

        // Add JS to load
        $this->scripts[]     = $this->adminThemeLoc.'/js/jquery.dcjqaccordion.2.7.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/jquery.scrollTo.min.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/jquery.nicescroll.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/respond.min.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/jquery.pulsate.min.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/slidebars.min.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/common-scripts.js';
        $this->scripts[]     = $this->adminThemeLoc.'/js/pulstate.js';
        View::share('scripts', $this->scripts);
    }

    /**
     * Admin Home Screen
     *
     * @return View
     */
    public function do_home() {
        // Share View Variables
        View::share('_title', 'Admin Home');

        return $this->renderTemplate('pages.AdminHome');
    }

    /**
     * Edit an existin role
     *
     * @param int $role_id Role ID
     *
     * @return View
     */
    public function do_editRole($role_id) {
        /** @var Role $role */
        $role = Role::find($role_id);

        // IF an invalid user ID was passed, throw a 404
        if (empty($role)) {
            return Redirect::to('admin.404');
        }

        // IF user submitted form
        if (Request::isMethod('post')) {
            $role->label    = Request::get('label');
            $role->isActive = Request::get('isActive');

            // IF there are valid values
            if (true === $this->_validateRole()) {
                // IF save() failed
                if (false === $role->save()) {
                    $this->addDashboardMessage('An error occurred.  We could not save the user role.', 'error');

                // save() worked
                } else {
                    // Redirect the user to the user edit page
                    $this->addDashboardMessage('Successfully saved role.');
                }
            }
        }

        // Share View Variables
        View::share('role', $role);
        View::share('_pageName', 'Edit '.$role->label);
        View::share('_title', 'Edit '.$role->label);
        View::share('_pageAction', '/Admin/editRole/'.$role->role_id);

        // Render the template
        return $this->renderTemplate('pages.Role');
    }

    /**
     * Add a new role
     *
     * @return View
     */
    public function do_addRole() {
        /** @var Role $role */
        $role = new Role();

        // IF user submitted form
        if (Request::isMethod('post')) {
            $role->label    = Request::get('label');
            $role->isActive = Request::get('isActive');

            // IF there are valid values
            if (true === $this->_validateRole()) {
                // IF save() failed
                if (false === $role->save()) {
                    $this->addDashboardMessage('An error occurred.  We could not save the user role.', 'error');

                // save() worked
                } else {
                    // Redirect the user to the user edit page
                    $this->addDashboardMessage('Successfully created role.');

                    // Redirect the user to the new route
                    $newRoute = '/Admin/editRole/'.$role->role_id;
                    return Redirect::to($newRoute)->with('dashboardMessages', $this->dashboardMessages);
                }
            }
        }

        // Share View Variables
        View::share('role', $role);
        View::share('_pageName', 'Add Role');
        View::share('_title', 'Add Role');
        View::share('_pageAction', '/Admin/addRole');

        // Render the template
        return $this->renderTemplate('pages.Role');
    }

    /**
     * List User Roles
     *
     * @return View
     */
    public function do_listRoles() {
        // Share View Variables
        View::share('Roles', Role::all());
        View::share('_pageName', 'List Roles');
        View::share('_title', 'List Roles');
        View::share('_pageAction', '/Admin/listRoles');

        // Include bootstrap's DataTable
        $this->_includeDataTable();

        // Add custom JS
        $this->scripts[] = '/js/ListRoles.js';

        // Render the template
        return $this->renderTemplate('pages.ListRoles');
    }

    /**
     * List User Accounts
     *
     * @return View
     */
    public function do_listUsers() {
        // Share View Variables
        View::share('Users', User::all());
        View::share('_pageName', 'List Users');
        View::share('_title', 'List Users');
        View::share('_pageAction', '/Admin/listUsers');

        // Include bootstrap's DataTable
        $this->_includeDataTable();

        // Add custom JS
        $this->scripts[] = '/js/ListUsers.js';

        // Render the template
        return $this->renderTemplate('pages.ListUsers');
    }

    /**
     * Add a new user account
     *
     * @return View
     */
    public function do_addUser() {
        /** @var User $user */
        $user = new User();

        // Initialize User Role Data
        $userRoleData = array();

        /** $var \Illuminate\Database\Eloquent\Collection $Roles */
        $Roles = Role::active()->get();

        // IF user submitted form
        if (Request::isMethod('post')) {
            $user->username  = Request::get('username');
            $user->firstName = Request::get('firstName');
            $user->lastName  = Request::get('lastName');
            $user->email     = Helper::email_unique(Request::get('email'));
            $user->isActive  = Request::get('isActive');
            $user->setPassword(Request::get('password'));

            // Get user roles
            if (Request::has('roles')) {
                $userRoleData = Request::get('roles');
            }

            // Run validation code
            $result1 = $this->_validateRoles($userRoleData, $Roles);
            $result2 = $this->_isValidUserAccount(true);


            // IF there are valid values
            if (true === $result1 && true === $result2) {
                // Attempt save()
                $result = $user->save();

                // IF save() failed
                if ($result === false) {
                    $this->addDashboardMessage('An error occurred.  We could not save the user.', 'error');

                // save() worked
                } else {
                    // Process roles
                    if (!empty($userRoleData)) {
                        $user->roles()->sync($userRoleData);
                    }

                    // Redirect the user to the user edit page
                    $this->addDashboardMessage('Successfully created user.');

                    // Redirect the user to the new route
                    $newRoute = '/Admin/editUser/'.$user->user_id;
                    return Redirect::to($newRoute)->with('dashboardMessages', $this->dashboardMessages);
                }
            }
        }

        // Share View Variables
        View::share('userRoleData', $userRoleData);
        View::share('user', $user);
        View::share('Roles', $Roles);
        View::share('_pageName', 'Add User');
        View::share('_title', 'Add User');
        View::share('_pageAction', '/Admin/addUser');

        // Render the template
        return $this->renderTemplate('pages.User');
    }

    /**
     * Add a new user account
     *
     * @param int $user_id User ID
     *
     * @return View
     */
    public function do_editUser($user_id) {
        /** @var User $user */
        $user = User::find($user_id);

        // IF an invalid user ID was passed, throw a 404
        if (empty($user)) {
            return Redirect::to('admin.404');
        }

        // Initialize User Role Data
        $userRoleData = array();

        /** $var \Illuminate\Database\Eloquent\Collection $Roles */
        $Roles = Role::active()->get();

        // IF user submitted form
        if (Request::isMethod('post')) {
            $user->firstName = Request::get('firstName');
            $user->lastName  = Request::get('lastName');
            $user->isActive  = Request::get('isActive');

            // IF the password changed
            $pw = Request::get('password');
            if (!empty($pw)) {
                $user->setPassword(Request::get('password'));
            }

            // Get user roles
            if (Request::has('roles')) {
                $userRoleData = Request::get('roles');
            }

            // Run validation code
            $result1 = $this->_validateRoles($userRoleData, $Roles);
            $result2 = $this->_isValidUserAccount();

            // IF there are valid values
            if (true === $result1 && true === $result2) {
                // Attempt save()
                $result = $user->save();

                // IF save() failed
                if ($result === false) {
                    $this->addDashboardMessage('An error occurred.  We could not save the user.', 'error');

                // save() worked
                } else {
                    // Process roles
                    if (!empty($userRoleData)) {
                        $user->roles()->sync($userRoleData);
                    }

                    // Add success message
                    $this->addDashboardMessage('Successfully updated user.');
                }
            }
        }

        // Share View Variables
        View::share('userRoleData', $user->roles->modelKeys());
        View::share('user', $user);
        View::share('Roles', $Roles);
        View::share('_pageName', 'Edit '.$user->firstName.' '.$user->lastName);
        View::share('_title', 'Edit '.$user->firstName.' '.$user->lastName);
        View::share('_pageAction', '/Admin/editUser/'.$user->user_id);

        // Render the template
        return $this->renderTemplate('pages.User');
    }

    /**
     * User to sign a user in
     *
     * @return Response
     */
    public function do_signIn() {
        // Initialize username
        $username = '';

        /**
         * Uses these values instead of the raw strings.  Helps prevent some automated bots from trying to log in.
         * username => ds*3dddx
         * password => psa#9ccc
         */
        // IF the user submitted the login form
        if (Request::isMethod('post') && Request::has('ds*3dddx')) {
            $username = Request::get('ds*3dddx');
            $pwHash   = User::getPWHash(Request::get('psa#9ccc'));

            // Attempt to fetch a user, which has identical credentials
            $Users = User::username($username)->pwHash($pwHash)->active()->get();

            // IF no users were found
            if ($Users->isEmpty()) {
                $this->addDashboardMessage('Invalid username / password combination.', 'error');

            // Login was correct
            } else {
                // Set user
                /** @var User user */
                $user = $Users->first();

                // Logs user in (sets necessary session data)
                $user->login();
                return redirect()->route('admin.home')->with('dashboardMessages', $this->dashboardMessages);
            }
        }
        // Set view data
        View::share('username', $username);
        View::share('_title', 'Sign In');
        View::share('_pageAction', '/signIn');

        // Render the template
        return $this->renderTemplate('pages.SignIn');
    }

    /**
     * Signs the current user out of their account
     *
     * @return Response
     */
    public function do_signOut() {
        // Remove all $_SESSION variables
        $this->_resetSession();

        // Provide feedback
        $this->addDashboardMessage('You have successfully logged out.');

        // Redirect the user to the sign in page
        return redirect()->route('admin.signin')->with('dashboardMessages', $this->dashboardMessages);
    }

    /**
     * Validates the values of a user account
     *
     * @param bool $newUser IF the request is for a new user
     *
     * @return bool
     */
    protected function _isValidUserAccount($newUser = false) {
        // The number of existing errors
        $errorCount = count($this->errorFields);

        // IF a new user account
        if ($newUser == true) {
            // Validates passwords
            $this->_processPassword();

            // Requested username
            $username = Request::get('username');

            // IF the username is too short
            if (is_null($username) || strlen($username) < config('app.minUserLength')) {
                $this->addDashboardMessage('The username is too short.', 'error');
                $this->errorFields[] = 'username';

            } else {
                // Attempt to find a user with the same username
                $Users = User::where('username', '=', $username);

                // IF a duplicate username was found
                if ($Users->count() > 0) {
                    $this->addDashboardMessage('The username is already in use.', 'error');
                    $this->errorFields[] = 'username';
                }
            }
            // Run email through validation
            $email = Helper::email_unique(Request::get('email'), true);
            if (empty($email)) {
                $this->addDashboardMessage('Please enter a valid email address.', 'error');
                $this->errorFields[] = 'email';

            // Test email against existing email addresses
            } else {
                // Attempt to find a user with the same username
                $Users = User::where('email', 'LIKE', $email);

                // IF a duplicate username was found
                if ($Users->count() > 0) {
                    $this->addDashboardMessage('The email address is already in use.', 'error');
                    $this->errorFields[] = 'email';
                }
            }

        // Edit a user account
        } else {
            // Fetch password, you cannot run empty() on a derived value which is dumb
            $password = Request::get('password');

            // IF the password changed
            if (!empty($password)) {
                $this->_processPassword();
            }
        }

        // IF firstName is empty
        $firstName = Request::get('firstName');
        if (empty($firstName)) {
            $this->addDashboardMessage('First name is required.', 'error');
            $this->errorFields[] = 'firstName';
        }

        // IF lastName is empty
        $lastName = Request::get('lastName');
        if (empty($lastName)) {
            $this->addDashboardMessage('Last name is required.', 'error');
            $this->errorFields[] = 'lastName';
        }

        // IF bit field is not valid
        $isActive = Request::get('isActive');
        if (!in_array($isActive, array('1', '0'))) {
            $this->errorFields[] = 'isActive';
            $this->addDashboardMessage('Is active field is required.', 'error');
        }

        // IF the error count has changed
        if ($errorCount != count($this->errorFields)) {
            return false;
        }
        return true;
    }

    /**
     * Processes the password fields for errors
     *
     * @return void
     */
    protected function _processPassword() {
        // IF the password is not strong enough
        if (!preg_match(config('app.pwRegex'), Request::get('password'))) {
            $this->addDashboardMessage('The password is not strong enough.', 'error');
            $this->errorFields[] = 'password';
            $this->errorFields[] = 'password2';
        }

        // IF the passwords do not equal
        if (Request::get('password') != Request::get('password2')) {
            $this->addDashboardMessage('The passwords do not equal.', 'error');
            $this->errorFields[] = 'password';
            $this->errorFields[] = 'password2';
        }
    }

    /**
     * Validate input values for roles
     *
     * @var array                                    $userRoles   User assigned roles
     * @var \Illuminate\Database\Eloquent\Collection $ActiveRoles Roles Active in system
     *
     * @return bool
     */
    protected function _validateRoles($userRoles, $ActiveRoles) {
        // Validate roles
        if (empty($userRoles)) {
            return true;
        }

        // Loop over input values to see if there are any erroneous values
        foreach ($userRoles as $role_id) {
            if (false === $ActiveRoles->contains($role_id)) {
                $this->addDashboardMessage('Invalid role values were detected.', 'error');
                return false;
            }
        }
        return true;
    }

    /**
     * Runs validation for new Role Objects
     *
     * @return bool
     */
    protected function _validateRole() {
        // Value to be returned
        $isValid  = true;

        // Fetch values
        $label    = Request::get('label');
        $isActive = Request::get('isActive');

        // IF label is empty
        if (empty($label)) {
            $this->addDashboardMessage('Role name is required.', 'error');
            $isValid = false;
        }

        // IF bit field is not valid
        if (!in_array($isActive, array('1', '0'))) {
            $this->addDashboardMessage('Is active field is required.', 'error');
            $isValid = false;
        }
        return $isValid;
    }
}
