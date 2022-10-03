<?php

namespace Devvly\Ffl\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;
use Illuminate\Support\Facades\Mail;
use Devvly\Ffl\Mail\FflExpirationNotification;

class EmailsAboutToExpireReminder extends Command
{
  protected $signature = 'emails-about-to-expire-reminder';

  protected $description = 'Send email reminders to FFLs when their license is about to expire, we want to send an email out asking them to upload a new version of their license. Then 1st of the next month following their expiration month, and one final one 30 days after its expired.';
  public $years = [
    "2020" => "0",
    "2021" => "1",
    "2022" => "2",
    "2023" => "3",
    "2024" => "4",
    "2025" => "5",
    "2026" => "6",
    "2027" => "7",
    "2028" => "8",
    "2029" => "9"];
  public $months = [
     "January" => "A",
     "February" =>"B",
     "March" => "C",
     "April" => "D",
     "May" => "E",
     "June" => "F",
     "July" => "G",
     "August" => "H",
     "September" => "I",
     "October" => "J",
     "November" => "K",
     "December" => "L"];

  public function handle()
  {


    // Get data
    $this->comment('Start emails-about-to-expire-reminder script');
    Log::info('Start emails-about-to-expire-reminder script');
    $this->comment('IMPORT | Check Ffls Expired last month');
    $this->info('IMPORT | Check Ffls Expired last month');
    $ffls_expired_from_one_month = $this->getDataExpiredFromOneMonth();
    $result=$this->handleDataExpiredFromOneMonth($ffls_expired_from_one_month);

    $this->comment('IMPORT | Check Ffls Expired yesterday');
    $this->info('IMPORT | Check Ffls Expired yesterday');
    $ffls_expired_yesterday = $this->getDataExpiredFromOneDay();
    $result=$this->handleDataExpiredFromOneDay($ffls_expired_from_one_month);

    $this->comment('IMPORT | Check Ffls To Be Expired Next Month');
    $this->info('IMPORT | Check Ffls To Be Expired Next Month');
    $ffls_expired_yesterday = $this->getDataToBeExpiredNextMonth();
    $result=$this->handleDataToBeExpiredNextMonth($ffls_expired_from_one_month);

    $this->comment('IMPORT | attributes Saved successfully');
    $this->info('IMPORT | DONE');
    Log::info('IMPORT | DONE');

  }

  /**
   * @return array|array[]
   */
  protected function getDataToBeExpiredNextMonth()
  {
         $today_date=date("Y-F-d");
         $first_day_of_the_month=date('Y-F-01');
         if($today_date!=$first_day_of_the_month){
           return [];
         }
         $date_array=explode('-',$today_date);
         $year_abbreviation=$this->years[$date_array[0]];
         $month_abbreviation=$this->months[$date_array[1]];
         $license_expire_date=$year_abbreviation.$month_abbreviation;
         $Ffls=app("Devvly\Ffl\Repositories\FflRepository")->findWhere(["license_expire_date"=>$license_expire_date]);
         return $Ffls;
  }

  protected function getDataExpiredFromOneDay()
  {
    $today_date=date("Y-F-d");
    $first_day_of_the_month=date('Y-F-01');
    if($today_date!=$first_day_of_the_month){
      return [];
    }
    $date_array=explode('-',$today_date);
    if($date_array[1]=="January"){
      $date_array[0]= (((int)$date_array[0]) - 1).toString();
    }

    $year_abbreviation=$this->years[$date_array[0]];
    $month_abbreviation=$this->months[date('F', strtotime('last month'))];
    $license_expire_date=$year_abbreviation.$month_abbreviation;
    $Ffls=app("Devvly\Ffl\Repositories\FflRepository")->findWhere(["license_expire_date"=>$license_expire_date]);
    return $Ffls;
  }

  protected function getDataExpiredFromOneMonth()
  {
    $today_date=date("Y-F-d");
    $first_day_of_the_month=date('Y-F-01');
    if($today_date!=$first_day_of_the_month){
      return [];
    }
    $date_array=explode('-',$today_date);
    if($date_array[1]=="January" || $date_array[1]=="February"){
      $date_array[0]= (((int)$date_array[0]) - 1).toString();
    }

    $year_abbreviation=$this->years[$date_array[0]];
    $month_abbreviation=$this->months[date("F", strtotime ( '-2 month' , strtotime ( date('Y-m-d') ) ))];
    $license_expire_date=$year_abbreviation.$month_abbreviation;
    $Ffls=app("Devvly\Ffl\Repositories\FflRepository")->findWhere(["license_expire_date"=>$license_expire_date]);
    return $Ffls;
  }



  protected function handleDataExpiredFromOneMonth($data){
    $this->output->progressStart(sizeof($data));
    foreach ($data as $ffl){
      $this->sendEmailForDataExpiredFromOneMonth($ffl);
      $this->output->progressAdvance();
    }
    $this->output->progressFinish();
  }

  protected function handleDataExpiredFromOneDay($data){
    $this->output->progressStart(sizeof($data));
    foreach ($data as $ffl){
      $this->sendDataExpiredFromOneDay($ffl);
      $this->output->progressAdvance();
    }
    $this->output->progressFinish();
  }

  protected function handleDataToBeExpiredNextMonth($data){
    $this->output->progressStart(sizeof($data));
    foreach ($data as $ffl){
      $this->sendDataToBeExpiredNextMonth($ffl);
      $this->output->progressAdvance();
    }
    $this->output->progressFinish();
  }
  protected function sendEmailForDataExpiredFromOneMonth($ffl){
    try {
      if($ffl->email){
        $notification="Please Note that your license has been expired from one month, Below is a link where you can upload your new license and update your ffl expiration date.";
        Mail::to($ffl->email)->send(new FflExpirationNotification($ffl,$notification));
      }
    } catch (\Exception $e) {
      $x=$e;
    }
  }
  protected function sendDataExpiredFromOneDay($ffl){
    try {
      if($ffl->email){
        $notification="Please Note that your license has been expired yesterday, Below is a link where you can upload your new license and update your ffl expiration date.";
        Mail::to($ffl->email)->send(new FflExpirationNotification($ffl,$notification));
      }
    } catch (\Exception $e) {
      $x=$e;
    }
  }
  protected function sendDataToBeExpiredNextMonth($ffl){
    try {
      if($ffl->email){
        $notification="Please Note that your license will be expired next month, Below is a link where you can upload your new license and update your ffl expiration date.";
        Mail::to($ffl->email)->send(new FflExpirationNotification($ffl,$notification));
      }
    } catch (\Exception $e) {
      $x=$e;
    }
  }

}
