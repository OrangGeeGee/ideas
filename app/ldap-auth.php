<?php

/**
 * Integrating this script into your project will give you access to the
 * basic data of the user that's accessing the site. Just include the script
 * and call getActiveUserDataViaLDAP() function. Needs php_ldap module to be
 * enabled.
 *
 * @author  Mattias Saldre
 * @updated 2014-02-03
 */

define('LDAP_GLOBAL_USER_KEY', 'PHP_AUTH_USER');  # Either PHP_AUTH_USER or REMOTE_USER.
define('LDAP_DOMAIN_OLD',      '@INT.HANSA.EE');
define('LDAP_DOMAIN_NEW',      '@FSPA.MYNTET.SE');
define('LDAP_SERVER',          'ldapbb.fspa.myntet.se');
define('LDAP_TREE',            'OU=FSB Users and Groups,DC=fspa,DC=myntet,DC=se');
define('LDAP_BIND_TREE',       'CN=p985eos,OU=Service accounts,OU=FSB Users and Groups,DC=fspa,DC=myntet,DC=se');
define('LDAP_BIND_PASSWORD',   'k9K1YxAz');
define('LDAP_FILTER',          '(&(objectClass=user)(samaccountName=%s))');
define('LDAP_DEFAULT_USER_ID', 't018ttt');

define('LDAP_ERROR_CONNECT',   'Could not connect to LDAP server.');
define('LDAP_ERROR_BIND',      'Error trying to bind: %s');
define('LDAP_ERROR_SEARCH',    'Error in search query: %s');
define('LDAP_ERROR_USER_NOT_FOUND', 'User "%s" not found.');


/**
 * @param string $message
 * @param string $details
 * @throws Exception
 */
function throwException($message = '', $details = '') {
  throw new Exception(sprintf($message, $details));
}


/**
 * Retrieves information about the current user. Falls back to
 * LDAP_DEFAULT_USER_ID if the user name can't be resolved.
 *
 * @return object
 */
function getActiveUserDataViaLDAP() {
  $userAccountId = isset($_SERVER[LDAP_GLOBAL_USER_KEY])
                 ? str_replace(array(LDAP_DOMAIN_OLD, LDAP_DOMAIN_NEW), '', $_SERVER[LDAP_GLOBAL_USER_KEY])
                 : LDAP_DEFAULT_USER_ID;
  $config = (object) array(
    'server'     => LDAP_SERVER,
    'tree'       => LDAP_TREE,
    'filter'     => sprintf(LDAP_FILTER, $userAccountId),
    'attributes' => array('displayname', 'mail')
  );
  $conn    = ldap_connect($config->server)
               or throwException(LDAP_ERROR_CONNECT);
  $binding = ldap_bind($conn, LDAP_BIND_TREE, LDAP_BIND_PASSWORD)
               or throwException(LDAP_ERROR_BIND, ldap_error($conn));
  $result  = ldap_search($conn, $config->tree, $config->filter, $config->attributes)
              or throwException(LDAP_ERROR_SEARCH, ldap_error($conn));
  $data    = ldap_get_entries($conn, $result);

  # Results have been fetched, no more point in keeping the connection open.
  ldap_close($conn);

  if ( $data['count'] == 0 )
  {
    throwException(LDAP_ERROR_USER_NOT_FOUND, $userAccountId);
  }

  return (object) array(
    'id' => $userAccountId,
    'name' => $data[0]['displayname'][0],
    'email' => $data[0]['mail'][0]
  );
}

$userData = getActiveUserDataViaLDAP();
$user = User::find($userData->id);

# The user isn't registered in our database.
if ( !$user )
{
  $user = User::create(array(
    'id' => $userData->id,
    'name' => $userData->name,
    'email' => $userData->email
  ));
}

if ( !Auth::user() )
{
  Auth::login($user);
  $user->touch();  # Timestamp user activity.
}
