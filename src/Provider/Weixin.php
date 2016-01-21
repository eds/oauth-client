<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/12 0012
 * Time: 下午 10:17
 */

namespace Jzyuchen\OAuthClient\Provider;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;

class Weixin extends AbstractProvider {


    /**
     * 授权作用域
     *
     * @var array
     */
    protected $scopes = ['snsapi_login'];

    public function __construct($options=[]){
        if (!array_has($options, 'redirectUri')){
            $options['redirectUri'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        }
        parent::__construct($options);
    }

    /**
     * Get the URL that this provider uses to begin authorization.
     *
     * @return string
     */
    public function urlAuthorize()
    {
        // TODO: Implement urlAuthorize() method.

        return 'https://open.weixin.qq.com/connect/qrconnect';
    }

    /**
     * Get the URL that this provider users to request an access token.
     *
     * @return string
     */
    public function urlAccessToken()
    {
        // TODO: Implement urlAccessToken() method.

        return 'https://api.weixin.qq.com/sns/oauth2/access_token';
    }

    /**
     * Get the URL that this provider uses to request user details.
     *
     * Since this URL is typically an authorized route, most providers will require you to pass the access_token as
     * a parameter to the request. For example, the google url is:
     *
     * 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$token
     *
     * @param AccessToken $token
     * @return string
     */
    public function urlUserDetails(AccessToken $token)
    {
        // TODO: Implement urlUserDetails() method.

        return 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    }

    /**
     * Given an object response from the server, process the user details into a format expected by the user
     * of the client.
     *
     * @param object $response
     * @param AccessToken $token
     * @return mixed
     */
    public function userDetails($response, AccessToken $token)
    {
        // TODO: Implement userDetails() method.
    }
}