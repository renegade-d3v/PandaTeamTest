<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(AMQPStreamConnection::class, function () {
            return new AMQPStreamConnection(
                Config::get('rabbitmq.host'),
                Config::get('rabbitmq.port'),
                Config::get('rabbitmq.user'),
                Config::get('rabbitmq.password'),
                Config::get('rabbitmq.vhost'),
            );
        });
    }
}
