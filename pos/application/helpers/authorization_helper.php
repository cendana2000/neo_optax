<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI = &get_instance();
        $token = self::validateToken($token);
        if (empty($token->jwt_timestamp)) {
            return false;
        }
        // if ($token != false && (now() - $token->jwt_timestamp < ($CI->config->item('token_timeout') * 60))) {
        //     return $token;
        // }
        if ($token != false) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        try {
            $CI = &get_instance();
            return JWT::decode($token, $CI->config->item('jwt_key'));
        } catch (\Exception $e) {
            $CI->output->set_content_type('application/json');
            $CI->output->set_output(json_encode(array(
                'success' => false,
                'message' => 'invalid token'
            ), JSON_UNESCAPED_UNICODE));
        }
    }

    public static function generateToken($data)
    {
        try {
            $CI = &get_instance();
            $data['jwt_timestamp'] = now();
            return JWT::encode($data, $CI->config->item('jwt_key'));
        } catch (\Exception $e) {
            $CI->output->set_content_type('application/json');
            $CI->output->set_output(json_encode(array(
                'success' => false
            ), JSON_UNESCAPED_UNICODE));
        }
    }

    public static function Auth()
    {
        $headers = getallheaders();
        $decodedToken = [];
        // Api Token
        if (array_key_exists('X-Persada-Access-Token', $headers)) {
            $token = $headers['X-Persada-Access-Token'];
            if ($token === 'pdpat_a83549af49a5a3abcbc91c1bfcc5567f') {
                $decodedToken['user_nama'] = 'App Pajak';
                return $decodedToken;
            }
        }

        // Bearer Token
        if (!array_key_exists('Authorization', $headers) && empty($headers['Authorization'])) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'not allowed',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $token = substr($headers['Authorization'], 7);
            $decodedToken = AUTHORIZATION::validateToken($token);
            if ($decodedToken == false) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'token invalid',
                ], JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                if (empty($decodedToken->session_db)) {
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'parameter session_db not found',
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
        }

        return $decodedToken;
    }

    public static function Guest()
    {
        $headers = getallheaders();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'not allowed',
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
