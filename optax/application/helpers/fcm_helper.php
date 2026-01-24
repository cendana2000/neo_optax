<?php defined('BASEPATH') or exit('No direct script access allowed');

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

if (!function_exists('send_fcm_notification_v1')) {
    function send_fcm_notification_v1($token, $title, $body, $data = [])
    {
        try {
            $CI = &get_instance();

            $conf       = $CI->db->get_where(
                'pajak_config',
                ['conf_code' => 'fcm_project_id']
            )->row();

            if (!$conf) {
                log_message('error', 'FCM project_id tidak ditemukan di config');
                return false;
            }

            $project_id = $conf->conf_value;

            $service_account_path = FCPATH . 'service-account.json';

            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $service_account_path
            );

            $middleware = new AuthTokenMiddleware($credentials);
            $stack      = HandlerStack::create();
            $stack->push($middleware);

            $client = new Client(['handler' => $stack]);

            $url = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";

            $accessToken = $credentials->fetchAuthToken()['access_token'];

            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'android' => [
                            'priority' => 'high'
                        ],
                        'data' => $data
                    ]
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $body = $response ? $response->getBody()->getContents() : 'no response';

            log_message('error', 'FCM ClientException');
            log_message('error', 'Status: ' . $e->getCode());
            log_message('error', 'Response: ' . $body);

            return true;
        } catch (RequestException $e) {
            // Error jaringan / timeout
            log_message('error', 'FCM RequestException: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            // Error lain
            log_message('error', 'FCM General Exception: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('notify_to_pemda')) {
    function notify_to_pemda($title, $message, $pemda_id = null)
    {
        $CI     = &get_instance();

        if ($pemda_id) {
            $CI->db->where('conf_user_login.pemda_id', $CI->session->userdata('pemda_id'));
        }

        $users  = $CI->db->from('conf_user_login')
            ->join('pajak_pegawai', 'pajak_pegawai.pegawai_id = conf_user_login.user_login_user_id')
            ->where('user_login_datetime_logout IS NULL', NULL, FALSE)
            ->where('pajak_pegawai.pegawai_role_access_id', '7b82ea8dadbc5b80fa888e27d469ce52') // pemda
            ->get()
            ->result();

        foreach ($users as $user) {
            send_fcm_notification_v1($user->user_login_fcm, $title, $message, [
                'notif_title'   => $title,
                'notif_message' => $message
            ]);
        }
    }
}

if (!function_exists('notify_to_stakeholders')) {
    function notify_to_stakeholders($title, $message)
    {
        $CI     = &get_instance();

        $users  = $CI->db->from('conf_user_login')
            ->join('pajak_pegawai', 'pajak_pegawai.pegawai_id = conf_user_login.user_login_user_id')
            ->where('user_login_datetime_logout IS NULL', NULL, FALSE)
            ->where_in('pajak_pegawai.pegawai_role_access_id', [
                '123',                              // superadmin
                '617c6495a8575cfc82d01df16c57d620', // kpk
                '226486229bf5f35ec7c7ad5537f8857c', // bank jatim
            ])
            ->get()
            ->result();

        foreach ($users as $user) {
            send_fcm_notification_v1($user->user_login_fcm, $title, $message, [
                'notif_title'   => $title,
                'notif_message' => $message
            ]);
        }
    }
}
