<?php

namespace Devvly\Ffl\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use MailerSend\LaravelDriver\MailerSendTrait;

/**
 * User Verification Mail class
 *
 * @author    Mohamad Kabalan <moe@2acommerce.com>
 * @copyright 2022  (http://www.devvly.com)
 */
class FflExpirationNotification extends Mailable
{
  use Queueable, SerializesModels, MailerSendTrait;



  /**
   * The seller instance.
   *
   * @var Ffl
   */
  public $ffl;

  /**
   * The seller instance.
   *
   * @var Notification
   */
  public $notification;


  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($ffl,$notification)
  {
    $this->ffl = $ffl;
    $this->notifiction = $notification;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $email=$this->ffl->email;
    $data['ffl']=$this->ffl;
    $data['notification']=$this->notifiction;
    if($email){

      try{
        $request = [
          "from" => [
            "email" => "MS_AlWzQO@2agunshow.com",
            "name" => "2agunshow"
          ],
          "to" => [
            [
              "email" => $this->user->email,
              "name" =>  $this->user->first_name.' '.$this->user->last_name
            ]
          ],
          "subject" => 'Account Verification',
          "html" => view('core::emails.account-verification')->with(['user' => $this->user])->render()
        ];

        $url = "https://api.mailersend.com/v1/email";
        $post = http_build_query($request);
        $ch = curl_init($url );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $headers = array();
        $headers[] = "Authorization: Bearer ".getenv('MAILERSEND_API_KEY');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($ch);
        curl_close($ch);
        $resp = json_decode($resp,true);
      }catch (\Exception $e){

      }
    }

  }
}
