<?php

namespace App;

class Controller
{
    protected function render($view, $data = [], $layout = "", $layout_data = [])
    {
        extract($data);

        if (!empty($layout)) {
            layout($layout, $data, "Views/$view.php", $layout_data);
        } else {
            include "Views/$view.php";
        }
    }

    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}