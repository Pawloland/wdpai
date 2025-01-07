<?php

class AppController
{
    private string $request;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
    }

    protected function isGet(): bool
    {
        return $this->request === 'GET';
    }

    protected function isPost(): bool
    {
        return $this->request === 'POST';
    }

    protected function render(?string $template = null, array $variables = []): void
    {
        $templatePath = 'public/views/' . $template . '.php';

//        $output = 'File not found';
        //extract variables from $_Session array and overwrite the keys with the values from the $variables array


        if (file_exists($templatePath)) {
//            extract($variables);
            extract($_SESSION['messages'] ?? []);
            unset($_SESSION['messages']);

            ob_start();
            include $templatePath;
            $output = ob_get_clean();
        }
        print $output;
    }
}