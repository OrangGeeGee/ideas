<?php

/**
 * Integrating this script into your project will give you access to the
 * basic data of the user that's accessing the site. Just include the script
 * and call getActiveUserDataViaLDAP() function.
 *
 * Needs php_ldap module to be enabled.
 *
 * @author  Mattias.Saldre@swedbank.ee
 * @updated 2015-06-28
 */

class LDAP {
  const LDAP_GLOBAL_USER_KEY = 'PHP_AUTH_USER';  # Either PHP_AUTH_USER or REMOTE_USER.
  const LDAP_DOMAIN_OLD =      '@INT.HANSA.EE';
  const LDAP_DOMAIN_NEW =      '@FSPA.MYNTET.SE';

  const LDAP_SERVER =          'ldapbb.fspa.myntet.se';
  const LDAP_TREE =            'OU=FSB Users and Groups,DC=fspa,DC=myntet,DC=se';
  const LDAP_BIND_TREE =       'CN=p985eos,OU=Service accounts,OU=FSB Users and Groups,DC=fspa,DC=myntet,DC=se';
  const LDAP_BIND_PASSWORD =   'k9K1YxAz';
  const LDAP_FILTER =          '(&(objectClass=user)(samaccountName=%s))';
  const LDAP_DEFAULT_USER_ID = 'msald';

  const LDAP_ERROR_CONNECT =   'Could not connect to LDAP server.';
  const LDAP_ERROR_BIND =      'Error trying to bind: %s';
  const LDAP_ERROR_SEARCH =    'Error in search query: %s';
  const LDAP_ERROR_USER_NOT_FOUND = 'User "%s" not found.';


  /**
   * @param string $message
   * @param string $details
   * @throws Exception
   */
  public static function throwException($message = '', $details = '') {
    throw new Exception(sprintf($message, $details));
  }


  /**
   * @param string $id
   * @return array
   */
  static public function getUserData($id) {
    $config = (object) [
      'server'     => self::LDAP_SERVER,
      'tree'       => self::LDAP_TREE,
      'filter'     => sprintf(self::LDAP_FILTER, $id),
      'attributes' => [
        'displayname',
        'mail',
        'title',
      ]
    ];
    $conn    = ldap_connect($config->server)
      or self::throwException(self::LDAP_ERROR_CONNECT);
    $binding = ldap_bind($conn, self::LDAP_BIND_TREE, self::LDAP_BIND_PASSWORD)
      or self::throwException(self::LDAP_ERROR_BIND, ldap_error($conn));
    $result  = ldap_search($conn, $config->tree, $config->filter, $config->attributes)
      or self::throwException(self::LDAP_ERROR_SEARCH, ldap_error($conn));
    $data    = ldap_get_entries($conn, $result);

    # Results have been fetched, no more point in keeping the connection open.
    ldap_close($conn);

    if ( $data['count'] == 0 ) {
      self::throwException(self::LDAP_ERROR_USER_NOT_FOUND, $id);
    }

    return [
      'id' => $id,
      'name' => utf8_encode($data[0]['displayname'][0]),
      'email' => $data[0]['mail'][0],
      'title' => isset($data[0]['title']) ? $data[0]['title'][0] : '',
    ];
  }


  /**
   * @return string
   */
  static public function getActiveUserId() {
    return isset($_SERVER[self::LDAP_GLOBAL_USER_KEY])
      ? str_replace(array(self::LDAP_DOMAIN_OLD, self::LDAP_DOMAIN_NEW), '', $_SERVER[self::LDAP_GLOBAL_USER_KEY])
      : self::LDAP_DEFAULT_USER_ID;
  }


  /**
   * @return array
   */
  static public function getActiveUserData() {
    return self::getUserData(self::getActiveUserId());
  }


  /**
   * Login the specified user. If the user doesn't exist on
   * the local or WHOIS database, then create it.
   *
   * @param string $uid=null
   */
  static public function login($uid = null) {

    if ( !$uid ) {
      $uid = self::getActiveUserId();
    }

    $userData = self::getUserData($uid);

    if ( !\App\WHOISUser::find($uid) ) {
      \App\WHOISUser::create($userData);
    }

    if ( !($localUser = \App\User::find($uid)) ) {
      $localUser = \App\User::create($userData);
    }

    \Auth::login($localUser);
  }
}
