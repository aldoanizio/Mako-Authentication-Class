Authentication Class for Mako Framework (www.http://makoframework.com)

Based on Gatekeeper Authentication library (https://github.com/arkivverket/gatekeeper)


====== Implementations ======

    :: Possibity to define login field (eg: login, email, nickname).
    :: Possibity to define pass field (eg: pass, password).
    :: Possibity to define table primary key used to store on Session and Cookie (Use "id" by default)
    :: Store Encrypted data in session and cookie
    :: Create multiples auth areas, and one of them with their own configs (eg: admin, users, members)


====== Example How to Use ======

    namespace app\controllers\users;
    
    use \mako\Auth;
    use \mako\View;
    use \app\models\User;
    
    class Index extends \mako\Controller
    {

        public function before()
        { 
            if (Auth::check('users'))
            {
                // Do Stuffs to load user from DB based on primary key stored on session/cookie
                $this->loggedUser = User::getUser(Auth::user('users')); 
            }
            else
            {
                // Redirect to User login URL
                Auth::url('users');
            }
        }
        
        
        // Other Class Methods
    }
