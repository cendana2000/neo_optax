<?php
class Post_controller_constructor  {

    function index()
    {
        if(isset($_SERVER['REQUEST_METHOD']) and strtolower($_SERVER['REQUEST_METHOD']) == 'options') die();

        $ci =& get_instance();

        $ci->load->helper(array('http'));
        $ci->load->library('user_agent');
        if ($ci->agent->is_mobile()) {
            return;
        }

        $account = $ci->load->model(['user/UserModel' => 'User']);

        $is_login = $account->User->islogin();
        $rules = $account->User->get_rules();
        $routeName = $this->getCurrentRouteName();
        $routePermission = $this->getCurrentRoutePermission();
        $routeIsAllowed = $account->User->get_rule_access($routeName);

        // 0 is public access
        //
        if($routePermission == 0)
        {
            // do nothing because link is public
        }

        // 1 is authenticated users, no matter who is it
        //
        else if($routePermission == 1)
        {
            $this->shouldLoggedIn();
        }

        // 2 is authorized users, set by role access
        //
        else if($routePermission == 2)
        {
            $this->shouldLoggedIn();

            if(!$routeIsAllowed)
            {
                setHttpResponseStatusHeader(403); // forbidden
                header('Content-Type: application/json');
                echo json_encode(array(
                    'success'=>false,
                    'code'   =>403,
                    'message'=>'Maaf, anda tidak diijinkan mengakses alamat ini'
                ));
                die();
            }
        }

        // 3 is current user, and only the user who create or prohibitted on itself data
        //
        else if($routePermission == 3)
        {
            // logic are not defined yet,
            // only check the token
            // the rest is on each conroller
            $this->shouldLoggedIn();
        }

        // any rule outside the registered rulePermission are set to notfound status
        //
        else
        {
            setHttpResponseStatusHeader(404); // unauthorized
            header('Content-Type: application/json');
            echo json_encode(array(
                'success'=>false,
                'code'   =>404,
                'message'=>'Maaf alamat ini tidak tersedia'
            ));
            die();
        }
    }

    protected function getRouteAccess($uriKeyOrString = null)
    {
        $ci =& get_instance();

        $accesLib = $ci->config->item('access_route');

        foreach ((array)$accesLib as $index => $access)
        {
            if(is_string($access)) $access = array($access);
            // if(
            //     (isset($access[0]) and is_string($access[0]) and $uriKeyOrString == $access[0])
            //     or
            //     (isset($access[2]) and is_string($access[2]) and $uriKeyOrString == $access[2])
            //     or
            //     (isset($access[2]) and is_array($access[2]) and in_array($uriKeyOrString, $access[2]))
            // )
            if(
                (isset($access[0]) and is_string($access[0]) and $uriKeyOrString == $access[0])
            )
            {
                return $access;
            }
        }
    }

    protected function getRoutePermission($uriKeyOrString = null)
    {
        $ci =& get_instance();

        $accesLib = $ci->config->item('access_route');
        if(!is_array($accesLib)) return -1;

        $access = $this->getRouteAccess($uriKeyOrString);

        if($access)
        {
            return (is_array($access) and array_key_exists(1, $access)) ? $access[1] : 0;
        }

        return -1;
    }

    protected function shouldLoggedIn()
    {
        $ci =& get_instance();

        $account = $ci->load->model('User/UserModel',true);

        $is_login = $account->User->islogin();

        // $token = getHttpRequestHeader($ci->config->item('token_key'));
        // if(!$token)
        if(!$is_login)
        {
            setHttpResponseStatusHeader(403); // unauthorized
            header('Content-Type: application/json');
            echo json_encode(array(
                'success'=>false,
                'code'   =>401,
                'message'=>'Silahkan login terlebih dahulu !'
            ));
            die();
        }
    }

    protected function getCurrentRouteAccess()
    {
        $ci =& get_instance();
        // $uri = $ci->uri->uri_string();
        $uri = $ci->router->class;
        return $this->getRouteAccess($uri);
    }

    protected function getCurrentRouteName()
    {
        $access = $this->getCurrentRouteAccess();

        if(is_string($access)) return $acces;

        if(is_array($access) and isset($access[0])) return $access[0];
    }

    protected function getCurrentRoutePermission()
    {
        $uri = $this->getCurrentRoute();
        return $this->getRoutePermission($uri);
    }

    protected function getCurrentRoute()
    {
      $ci =& get_instance();
      // return $ci->uri->uri_string();
      return $ci->router->class;
    }
}

/* End of file post_controller_constructor.php */
/* Location: ./application/hooks/post_controller_constructor.php */
