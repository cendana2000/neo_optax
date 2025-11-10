<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use \Amp\Beanstalk\BeanstalkClient;
use \Amp\Loop;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if (!function_exists('addJobToQueue')) {
    
    function addJobToQueue($tube = null, $payload = null){
        
        if(!$tube) return;
        $baseQueue = $_SERVER['BASE_QUEUE'];
        Loop::run(function () use ($start, $prodBeanstalk, $tube, $baseQueue, $payload) 
        {
            $prodBeanstalk = new BeanstalkClient("tcp://".$baseQueue);
            yield $prodBeanstalk->use($tube);

            $dataPayload = json_encode($payload);

            $jobId = yield $prodBeanstalk->put($dataPayload);

            $prodBeanstalk->quit();
        });
    }
}

if (!function_exists('addJobToRabbit')) {

    function addJobToRabbit($tube = null, $payload = null)
    {

        if (!$tube) return;
        $tube = $_SERVER['APP_NAME']."-".$tube;
        $connection = new AMQPStreamConnection($_SERVER['RMQ_HOST'], $_SERVER['RMQ_PORT'], $_SERVER['RMQ_USERNAME'], $_SERVER['RMQ_PASSWORD']);
        $channel = $connection->channel();
        $channel->queue_declare($tube, false, true, false, false);
        $msg = new AMQPMessage(json_encode($payload),array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
        $channel->basic_publish($msg, '', $tube);
        $channel->close();
        $connection->close();
    }
}