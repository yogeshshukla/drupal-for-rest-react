<?php

namespace Drupal\simple_oauth_extend;

use League\OAuth2\Server\ResponseTypes\BearerTokenResponse;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class BearerTokenExtendResponse extends BearerTokenResponse {

  /**
   * Add custom fields to your Bearer Token response here, then override
   * AuthorizationServer::getResponseType() to pull in your version of
   * this class rather than the default.
   *
   * @param AccessTokenEntityInterface $accessToken
   *
   * @return array
   */
  protected function getExtraParams(AccessTokenEntityInterface $accessToken)
  {
    $uid = (int)$accessToken->getUserIdentifier();
    $user = \Drupal\user\Entity\User::load($uid);

    return [
        'user_id' => $uid,
        'user' => $user->getUsername()
    ];
  }
}
