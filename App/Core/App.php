<?php

namespace App\Core;

class App
{
    protected $router;
    protected $errorMiddleware;
    protected $timezone;

    public function __construct(Router $router, ErrorMiddleware $errorMiddleware)
    {
        $this->router = $router;
        $this->errorMiddleware = $errorMiddleware;
        $this->timezone = new Config();
        date_default_timezone_set($this->timezone->get('timezone'));
    }

    public function run()
    {
        $this->router->loadRoutes();
        $this->errorMiddleware->handle();
        $error = $this->errorMiddleware->getError();
        if ($error)
            $this->handleError($error);
    }

    protected function handleError($error)
    {
        // You can implement your own error handling logic here
        // For example, you can display an error message to the user
        // or redirect them to a custom error page.
        die("An error occurred: " . $error['message']);
    }
}
