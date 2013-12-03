<?php

define('ERR_LDAP_CONNECT', 'Could not connect to LDAP server.');
define('ERR_LDAP_BIND', 'Error trying to bind: ');
define('ERR_LDAP_SEARCH', 'Error in search query: ');

/**
 * @param string $message
 * @param int    $code
 */
function abort($message = '', $code = 404) {
  App::abort($code, $message);
  exit;
}


/**
 * Verify the user.
 * ----------------------------------------------------------------------------
 */
$userAccountId = ( isset($_SERVER['PHP_AUTH_USER']) )
  ? str_replace('@INT.HANSA.EE', '', $_SERVER['PHP_AUTH_USER'])  # user@INT.HANSA.EE
  : 'msald';  # Provide a fallback user for local development.

$config = (object) array(
  'server'     => 'ldap-test.int.hansa.ee',
  'tree'       => 'OU=Employees,OU=Accounts,DC=int,DC=hansa,DC=ee',
  'filter'     => "(&(objectClass=user)(samaccountName={$userAccountId}))",
  'attributes' => array('displayname', 'mail')
);

$conn     = ldap_connect($config->server) or abort(ERR_LDAP_CONNECT);
$binding = ldap_bind($conn) or abort(ERR_LDAP_BIND . ldap_error($conn));
$result  = ldap_search($conn, $config->tree, $config->filter, $config->attributes)
             or abort(ERR_LDAP_SEARCH . ldap_error($conn));
$data    = ldap_get_entries($conn, $result);

ldap_close($conn);

if ( $data['count'] == 0 )
{
  abort("User '$userAccountId' not found.", 301);
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

Auth::login($user);

# Timestamp user activity.
$user->touch();
