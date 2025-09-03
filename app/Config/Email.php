<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail;
    public string $fromName;
    public string $recipients;

    public string $userAgent = 'CodeIgniter';
    public string $protocol;
    public string $mailPath = '/usr/sbin/sendmail';

    public string $SMTPHost;
    public string $SMTPUser;
    public string $SMTPPass;
    public int    $SMTPPort;
    public int    $SMTPTimeout = 30;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPCrypto;

    public bool   $wordWrap = true;
    public int    $wrapChars = 76;
    public string $mailType = 'html';
    public string $charset  = 'UTF-8';
    public bool   $validate = true;
    public int    $priority = 3;

    public string $CRLF    = "\r\n";
    public string $newline = "\r\n";

    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN          = false;

    public function __construct()
    {
        parent::__construct();

        // Ambil semua setting dari .env
        $this->fromEmail  = env('email.fromEmail', 'noreply@localhost');
        $this->fromName   = env('email.fromName', 'MyApp');
        $this->protocol   = env('email.protocol', 'smtp');
        $this->SMTPHost   = env('email.SMTPHost', '');
        $this->SMTPUser   = env('email.SMTPUser', '');
        $this->SMTPPass   = env('email.SMTPPass', '');
        $this->SMTPPort   = (int) env('email.SMTPPort', 587);
        $this->SMTPCrypto = env('email.SMTPCrypto', 'tls');
    }
}
