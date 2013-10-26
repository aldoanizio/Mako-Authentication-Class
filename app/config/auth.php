<?php

//---------------------------------------------
// Authentication configuration
//---------------------------------------------

return array
(   
    /**
     * You can define as many auth section as you want.
     *
     * secret    : Secret key used to store session. Use different secret for different auth areas
     * username  : Table username field used to query registry from DB. Eg: username, email, login, nickname
     * password  : Table password field used to validate username/password combination
     * table     : Table where user is stored; Eg: users, administrators, members
     * pk        : Table primary key field used to store session and cookie information; Eg: id
     * url       : Login URL Route. Used to redirect user to login screen and avoid redirect looping
     * redirect  : Define where to redirect user after Post Login. Set False if wish not redirect
     * ttl       : Cookie Time to live - if omitted or set to 0 the cookie will expire when the browser closes
     */
    
    'sections' => array
    (
        'users' => array
        (
            'secret'   => 'tx6T7nE7ZV5UinFSUngqFW04FlzhReBe',
            'login'    => 'email',
            'password' => 'password',
            'table'    => 'users',
            'pk'       => 'id',
            'url'      => 'users/login',
            'redirect' => 'users',
            'ttl'      => 3600 * 720, // Eg: 30 Days
        ),
    ),
);

/** -------------------- End of file --------------------**/
