Authentication Class for Mako Framework (www.http://makoframework.com)

Based on Gatekeeper Authentication library (https://github.com/arkivverket/gatekeeper)


====== Implementations ======

    :: Possibity to define login field (eg: login, email, nickname).
    :: Possibity to define pass field (eg: pass, password).
    :: Create multiples auth areas, and one of them with their own configs (eg: admin, users, members)


====== Exemple How to Use ======


    public function before()
    { 
        if (Auth::check('users'))
        {
            // Do Stuffs to load user from DB based on email stored on session/cookie
            $this->loggedUser = User::getUser(Auth::user('admin'), 'email'); 
        }
        else
        {
            // Redirect to User login URL
            Auth::url('users');
        }
    }
