<?php
/**
 * Minimal placeholder for PHPMailer just for reference.
 * In production, replace with full PHPMailer library.
 */
class PHPMailer
{
    public $isSMTP = false;
    public $Host;
    public $SMTPAuth = true;
    public $Username;
    public $Password;
    public $SMTPSecure = 'tls';
    public $Port = 587;
    public $CharSet = 'UTF-8';
    public $From;
    public $FromName;
    public $Subject;
    public $Body;
    public $AltBody;
    private $to = [];

    public function isSMTP()
    {
        $this->isSMTP = true;
    }

    public function addAddress($email, $name = '')
    {
        $this->to[] = [$email, $name];
    }

    public function send()
    {
        $headers = "From: {$this->From}\r\n" .
                   "Reply-To: {$this->From}\r\n" .
                   "Content-Type: text/html; charset={$this->CharSet}\r\n";
        $to = array_map(function($t){ return $t[0]; }, $this->to);
        return mail(join(',', $to), $this->Subject, $this->Body, $headers);
    }
}
?>
