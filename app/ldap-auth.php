<?php

define('GLOBAL_USER_KEY', 'PHP_AUTH_USER');  # Either PHP_AUTH_USER or REMOTE_USER.
define('LDAP_DOMAIN', '@INT.HANSA.EE');
define('LDAP_DEFAULT_USER_ID', 'msald');  # Set this to your own for local dev.
define('LDAP_ERR_CONNECT', 'Could not connect to LDAP server.');
define('LDAP_ERR_BIND', 'Error trying to bind: %s');
define('LDAP_ERR_SEARCH', 'Error in search query: %s');
define('LDAP_ERR_USER_NOT_FOUND', 'User "%s" not found.');


/**
 * @param string $message
 * @param string $details
 * @param int    $code
 */
function abort($message = '', $details = '', $code = 404) {
  App::abort($code, sprintf($message, $details));
  exit;
}


/**
 * Verify the user.
 * ----------------------------------------------------------------------------
 */
$userAccountId = isset($_SERVER[GLOBAL_USER_KEY])
  ? str_replace(LDAP_DOMAIN, '', $_SERVER[GLOBAL_USER_KEY])
  : LDAP_DEFAULT_USER_ID;

$config = (object) array(
  'server'     => 'ldap-test.int.hansa.ee',
  'tree'       => 'OU=Employees,OU=Accounts,DC=int,DC=hansa,DC=ee',
  'filter'     => "(&(objectClass=user)(samaccountName={$userAccountId}))",
  'attributes' => array('displayname', 'mail')
);

$conn    = ldap_connect($config->server)
             or abort(LDAP_ERR_CONNECT);
$binding = ldap_bind($conn)
             or abort(LDAP_ERR_BIND, ldap_error($conn));
$result  = ldap_search($conn, $config->tree, $config->filter, $config->attributes)
             or abort(LDAP_ERR_SEARCH, ldap_error($conn));
$data    = ldap_get_entries($conn, $result);

# Results have been fetched, no more point in keeping the connection open.
ldap_close($conn);

if ( $data['count'] == 0 )
{
  abort(LDAP_ERR_USER_NOT_FOUND, $userAccountId, 301);
}

$user = User::find($userAccountId);

# The user isn't registered in our database.
if ( !$user )
{
  $user = User::create(array(
    'id' => $userAccountId,
    'name' => $data[0]['displayname'][0],
    'email' => $data[0]['mail'][0]
  ));
}

if ( !Auth::user() )
{
  Auth::login($user);
  $user->touch();  # Timestamp user activity.
}
