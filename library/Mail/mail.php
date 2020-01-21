<?php
require 'sendgrid/vendor/autoload.php';

class Mail{
    private $subject;
    private $to;
    private $body;
    private $from;
    private $senderName;
    private $key ='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';
    public function __construct($to=null,$from=null,$subject=null,$body=null,$senderName=null){
        if($to !=null){
            $this->to = $to;
        }else{
            return false;
        }
        if($from !=null){
            $this->from = $from;
        }else{
            return false;
        }
        if($body !=null){
            $this->body = $body;
        }else{
            return false;
        }
        if($subject !=null){
            $this->subject = $subject;
        }else{
            return false;
        }
        if($senderName !=null){
            $this->senderName = $senderName;
        }else{
            return false;
        }
        return $this;
    }
    public function sendMail(){
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom($this->from, $this->senderName);
        $email->setSubject($this->subject);
        $email->addTos($this->to);
        $email->addContent("text/plain", $this->body);
        $email->addContent("text/html", $this->body);

        $sendgrid = new \SendGrid($this->key);
        try {
            $response = $sendgrid->send($email);
            if($response->statusCode() == 202){
                return true;
            }

        } catch (Exception $e) {

        }

    }


}
