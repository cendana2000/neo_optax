<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PostingPajak extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();

		$this->load->model(array(
			'postingpajak/RealisasiModel' => 'realisasi',
		));
    }

	public function index(){
		$user = $this->session->userdata();
		$payload = [
			'data' => ['a' => 'a', 'b' => 'b'],
			'user' => $user,
		];

		addJobToRabbit('store_posting_pajak', $payload);

		$this->response([
			'message'=> 'wee',
		]);
	}

	public function consumeData()
	{
		$connection = new AMQPStreamConnection($_SERVER['RMQ_HOST'], $_SERVER['RMQ_PORT'], $_SERVER['RMQ_USERNAME'], $_SERVER['RMQ_PASSWORD']);
        $channel = $connection->channel();
        $channel->queue_declare($_SERVER['APP_NAME']."-store_posting_pajak", false, true, false, false);
        $channel->basic_consume($_SERVER['APP_NAME']."-store_posting_pajak", '', false, true, false, false, function($msg){
			$cmd = json_decode($msg->body,true);

			// code
			$this->realisasi->process_store($cmd);
		});
        while ($channel->is_open()) {
            $channel->wait();
        }
	}
}