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


    protected $apiDomain = 'https://api.weixin.qq.com';
    protected $openid = ''; // only stupid tencent offers this..


    /**
     * 授权作用域
     *
     * @var array
     */
    public $scopes = ['snsapi_login'];


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

    public function getAuthorizationUrl($options = [])
    {
        $this->state = isset($options['state']) ? $options['state'] : md5(uniqid(rand(), true));

        $params = [
            'appid' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'state' => $this->state,
            'scope' => is_array($this->scopes) ? implode($this->scopeSeparator, $this->scopes) : $this->scopes,
            'response_type' => isset($options['response_type']) ? $options['response_type'] : 'code',
            'approval_prompt' => isset($options['approval_prompt']) ? $options['approval_prompt'] : 'auto',
        ];

        return $this->urlAuthorize().'?'.$this->httpBuildQuery($params, '', '&');
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

        return 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->clientId.'&grant_type=refresh_token&refresh_token='.$token->refreshToken;
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

        $response = $this->fetchUserDetails($token);

        // pickup openid
        $first_open_brace_pos = strpos($response, '{');
        $last_close_brace_pos = strrpos($response, '}');
        $response = json_decode(substr(
            $response,
            $first_open_brace_pos,
            $last_close_brace_pos - $first_open_brace_pos + 1
        ));

        $this->openid = $response->openid;
        // fetch QQ user profile
        $params = [
            'access_token' => '23232323', //$token->accessToken,
            'openid' => $this->openid
        ];
        $request = $this->httpClient->get($this->apiDomain . '/sns/userinfo?' . http_build_query($params));
        $response = json_decode($request->send()->getBody(),true);

        // check response status
        if ($response["errcode"]) {
            // handle tencent's style exception.
            $result['code'] = $response["errcode"];
            $result['message'] = $response["errmsg"];
            throw new \League\OAuth2\Client\Exception\IDPException($result);
        }

        return (new User)->setRaw($response)->setToken($token)->map([
            'id' => $this->openid,
            'nickname' => $response['nickname'],
            'name' => '',
            'email' => '',
            'avatar' => $response['headimgurl'],
            'gender' => $response['sex']
        ]);

    }
    
}