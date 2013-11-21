<?php

namespace mako;

use \mako\Config;
use \mako\Cookie;
use \mako\Database;
use \mako\Request;
use \mako\Response;
use \mako\Session;
use \mako\security\Password;
use \mako\security\Crypto;
use \mako\URL;

/**
 * Authentication Class
 *
 * @author     Aldo Anizio Lugão Camacho
 * @copyright  (c) 2013 Aldo Anizio Lugão Camacho
 * @license    http://www.makoframework.com/license
 */

class Auth
{
    //---------------------------------------------
    // Class properties
    //---------------------------------------------
    
    // Nothing here
    
    //---------------------------------------------
    // Class constructor, destructor etc ...
    //---------------------------------------------
    
    /**
     * Protected constructor since this is a static class.
     *
     * @access  protected
     */
    
    protected function __construct()
    {
        // Nothing here
    }
    
    //---------------------------------------------
    // Class methods
    //---------------------------------------------

    /**
     * Logs in a user with a valid login/password combination.
     * 
     * @access  public
     * @param   string   $section   Section Name
     * @param   string   $login     User login
     * @param   string   $password  User password
     * @param   boolean  $remember  (optional) Set a remember me cookie?
     * @return  boolean
     */

    public static function login($section, $login, $password, $remember = false)
    {    
        // Attempt do Login
        $user = self::attempt($section, $login, $password);

        // If Authenticate successfull
        if($user)
        {
            $secret = Config::get("lifestream::auth.sections.{$section}.secret"); // Section Secret
            $pk = Config::get("lifestream::auth.sections.{$section}.pk"); // Table Primary Key

            // Encrypted Primary Key
            $encryptedPk = Crypto::encrypt($user->{$pk});

            // Regenerate Session
            Session::regenerate();

            // Store Encrypted User Primary Key in Session
            Session::remember($secret, $encryptedPk);

            // If Remember is Set
            if($remember == true)
            {    
                // Store Encrypted User Primary Key in Cookie
                Cookie::set($secret, $encryptedPk, (Config::get("lifestream::auth.sections.{$section}.ttl")));
            }
            else
            {    
                // Delete Cookie
                Cookie::delete($secret);
            }

            return true;
        }
    }

    /**
     * Returns the attemptd user if the user login and password combo matches and FALSE if not.
     * 
     * @access  protected
     * @param   string    $section   Section Name
     * @param   string    $login     User login
     * @param   string    $password  User password
     * @param   boolean   $force     (optional) Skip the password check?
     * @return  object
     */

    protected static function attempt($section, $login, $password, $force = false)
    {
        // Section Configs
        $authtable = Config::get("lifestream::auth.sections.{$section}.table");
        $authLogin = Config::get("lifestream::auth.sections.{$section}.login");
        $authPassword = Config::get("lifestream::auth.sections.{$section}.password");
        
        // User from DB
        $user = Database::table($authtable)->where($authLogin, '=', $login)->first();

        // Validate User/Password
        if ( $user !== false && Password::validate($password, $user->{$authPassword}) )
        {
            return $user;
        }
    }

    /**
     * Logs the user out.
     * 
     * @access  public
     * @param   string   $section   Section Name
     */

    public static function logout($section = null)
    {
        // Regenarate Session
        Session::regenerate(); 
        
        // Destroy Session
        Session::forget(Config::get("lifestream::auth.sections.{$section}.secret")); 

        // Delete Cookie
        Cookie::delete(Config::get("lifestream::auth.sections.{$section}.secret"));
    }    

    /**
     * Returns the logged user or NULL if no user is logged in.
     * 
     * @access  public
     * @return  mixed
     */

    public static function user($section = null)
    {
        // Get user from Session / Cookie
        $user = self::check($section);

        if ($user)
        {
            // Decrypt User
            return Crypto::decrypt($user);
        }
        
        // Return Decrypted User
        return $user;
    }

    /**
     * Returns TRUE if Session/Cookie exists or FALSE if it isn`t.
     * 
     * @access  public
     * @return  boolean
     */

    public static function check($section = null)
    {
        $session = Session::get(Config::get("lifestream::auth.sections.{$section}.secret"), null);
        
        $cookie = Cookie::get(Config::get("lifestream::auth.sections.{$section}.secret"), null);

        if (isset($session)) {
            
            return $session;

        } elseif (isset($cookie)) {
            
            return $cookie;

        } else {

            return false;
        }
    }

    /**
     * Redirects to Login Screen
     *
     * @access  public
     * @param   string   $section   Section Name
     */

    public static function url($section = null)
    {
        // New Request Object
        $request = new Request();

        /**
         * Check if current screen is not the login screen
         * Check if Current URL belongs login
         */
        if ( ($request->method() == 'GET') && (!URL::matches(Config::get("lifestream::auth.sections.{$section}.url"))) )
        {
            $response = new Response();

            $response->redirect(Config::get("lifestream::auth.sections.{$section}.url"));
        }
    }

}
