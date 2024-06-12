<?php
namespace App\Helpers;
use App\Models\Notification;
use DB;
class NotificationHelper
{

    public static function createNotification($senderId,$recevierId, $title, $module, $moduleId, $message)
   {
        $notification = new Notification();
        $notification->sender_id = $senderId;
        $notification->recevier_id = $recevierId;
        $notification->title = $title;
        $notification->module = $module;
        $notification->module_id = $moduleId;
        $notification->message = $message;
        $notification->save();
          return $notification;
     }



}