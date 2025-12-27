<?php defined('BASEPATH') or exit('No direct script access allowed');

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

if (!function_exists('send_fcm_notification_v1')) {
    function send_fcm_notification_v1($token, $title, $body, $data = [])
    {
        $CI                     = &get_instance();

        $conf                   = $CI->db->get_where('pajak_config', ['conf_code' => 'fcm_project_id'])->row();
        $project_id             = $conf->conf_value;
        $service_account_path   = FCPATH . 'service-account.json';
        $credentials            = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/cloud-platform',
            $service_account_path
        );
        $middleware     = new AuthTokenMiddleware($credentials);
        $stack          = HandlerStack::create();
        $stack->push($middleware);

        $client         = new Client(['handler' => $stack]);
        $url            = "https://fcm.googleapis.com/v1/projects/$project_id/messages:send";
        $response       = $client->post($url, [
            'headers'       => [
                'Authorization' => 'Bearer ' . $credentials->fetchAuthToken()['access_token'],
                'Content-Type'  => 'application/json',
            ],
            'json'          => [
                'message'   => [
                    'token'         => $token,
                    'android'       => [
                        'priority'  => 'high'
                    ],
                    'notification'  => [
                        'title' => $title,
                        'body'  => $body
                    ],
                    'data'  => $data
                ]
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}

if (!function_exists('notify_to_pemda')) {
    function notify_to_pemda($title, $message)
    {
        $CI     = &get_instance();

        $users  = $CI->db->from('conf_user_login')
            ->join('pajak_pegawai', 'pajak_pegawai.pegawai_id = conf_user_login.user_login_user_id')
            ->where('user_login_datetime_logout IS NULL', NULL, FALSE)
            ->where('conf_user_login.pemda_id', $CI->session->userdata('pemda_id'))
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
            ->where('conf_user_login.pemda_id', $CI->session->userdata('pemda_id'))
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
