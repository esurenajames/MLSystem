<?php

class PHPMailer_library
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load()
    {
        require_once(APPPATH."third_party/src/PHPMailer.php");
        require_once(APPPATH."third_party/src/SMTP.php");
        require_once(APPPATH."third_party/src/Exception.php");
        $objMail = new PHPMailer\PHPMailer\PHPMailer(true);
        return $objMail;
    }
}

?>