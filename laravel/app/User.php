<?php
namespace app;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Session;

/**
 * User Model
 * File : /laravel/app/User.php
 *
 * PHP version 5.3
 *
 * @category Celania
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */

/**
 * User - Base model for user details
 *
 * @category Celania
 * @package  Celania
 * @author   Jason Turpin <jasonaturpin@gmail.com>
 */
class User extends Model {
    /**
     * @var string - The database table used by the model
     */
    protected $table = 'Users';

    /**
     * @var string - Primary key
     */
    protected $primaryKey = 'user_id';

    /**
     * @var array - Fields allowed for mass assignment
     */
    protected $fillable = array('firstName', 'lastName', 'email');

    /**
     * Adds a username condition to the WHERE statement
     *
     * @param Object $query    Query Object
     * @param string $username Username being searched for
     *
     * @return Object
     */
    public function scopeUsername($query, $username) {
        return $query->where('username', '=', $username);
    }

    /**
     * Adds a username condition to the WHERE statement
     *
     * @param Object $query  Query Object
     * @param string $pwHash Password hash being searched for
     *
     * @return Object
     */
    public function scopePwHash($query, $pwHash) {
        return $query->where('pwHash', '=', $pwHash);
    }

    /**
     * Fetches active users
     *
     * @param Object $query Query Object
     *
     * @return Object
     */
    public function scopeActive($query) {
        return $query->where('isActive', '=', '1');
    }

    /**
     * Builds a password hash
     *
     * @param string $password Password to be hashed
     *
     * @return string
     */
    public static function getPWHash($password) {
        return hash("sha256", $password.config('app.pwSalt'));
    }

    /**
     * Logs a user in (sets necessary $_SESSION data)
     *
     * @return void
     */
    public function login() {
        // Build Roles Array
        $userRoles = array();
        foreach ($this->roles->toArray() as $role) {
            $userRoles[] = $role['label'];
        }

        // Place values into the SESSION
        Session::put('_user_id', $this->user_id);
        Session::put('_username', $this->username);
        Session::put('_loginHash', $this->fetchHash());
        Session::put('_roles', $userRoles);
    }

    /**
     * IF the current user has the given role
     *
     * @param string $roleStr Role string
     *
     * @return bool
     */
    public function roleTest($roleStr) {
        // Active User ID
        $user_id = Session::get('_user_id');

        // The current user is not the active user
        if ($user_id !== $this->user_id) {
            return false;
        }

        // Array of roles
        $roles = Session::get('_roles');

        // IF the role is in the user roles array
        return in_array($roleStr, $roles);
    }

    /**
     * Returns the login hash for the user
     *
     * @return string
     */
    public function fetchHash() {
        return hash("sha256", $this->user_id.$this->username.config('app.loginSalt'));
    }

    /**
     * Checks if a user is logged in
     *
     * @return bool
     */
    public function isLoggedIn() {
        // Fetch $_SESSION data
        $sessionData = Session::all();

        // IF the user has valid login credentials
        $validLogin = false;

        // Check to see if user is logged in
        if (isset($sessionData['_user_id']) && isset($sessionData['_username']) && isset($sessionData['_loginHash'])) {

            // IF the usernames match and hash equals, the user is valid
            if (!is_null($this->user_id) && $this->user_id == $sessionData['_user_id']
                && $this->username == $sessionData['_username'] && $sessionData['_loginHash'] == $this->fetchHash()
            ) {
                $validLogin = true;
            }
        }
        return $validLogin;
    }

    /**
     * Joins with Roles
     *
     * @return Object
     */
    public function roles() {
        return $this->belongsToMany('\App\Role', 'UsersRoles', 'user_id', 'role_id');
    }

    /**
     * Sets password hash
     *
     * @param string $password Password
     *
     * @return void
     */
    public function setPassword($password) {
        $this->pwHash = hash("sha256", $password.config('app.pwSalt'));
    }
/*
+------------+------------------+------+-----+---------------------+-----------------------------+
| Field      | Type             | Null | Key | Default             | Extra                       |
+------------+------------------+------+-----+---------------------+-----------------------------+
| user_id    | int(11) unsigned | NO   | PRI | NULL                | auto_increment              |
| firstName  | varchar(100)     | NO   |     | NULL                |                             |
| lastName   | varchar(100)     | NO   |     | NULL                |                             |
| username   | varchar(20)      | NO   | UNI | NULL                |                             |
| email      | varchar(100)     | NO   | UNI | NULL                |                             |
| pwHash     | varchar(255)     | NO   |     | NULL                |                             |
| isActive   | tinyint(1)       | NO   |     | 1                   |                             |
| created_at | timestamp        | NO   |     | CURRENT_TIMESTAMP   | on update CURRENT_TIMESTAMP |
| updated_at | timestamp        | NO   |     | 0000-00-00 00:00:00 |                             |
+------------+------------------+------+-----+---------------------+-----------------------------+
*/
}
