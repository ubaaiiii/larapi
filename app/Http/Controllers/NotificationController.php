<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\OffersNotification;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $user = User::find($id);
        dd($user->notifications);
    }

    public function sendPushNotif($transid,$id_user,$tipe,$comment)
    {
        $tujuan = User::find($id_user);

        switch ($tipe) {
            case 'rollback':
                $icon = "refresh-ccw";
                break;
            case 'new':
                $icon = "square";
                break;
            case 'approve':
                $icon = "check-square";
                break;
            case 'paid':
                $icon = "circle";
                break;
            case 'polis':
                $icon = "check-circle";
                break;
            
            default:
                $icon = "";
                break;
        }

        $data = [
            'transid'   => $transid,
            'icon'      => $icon,
            'text'      => $comment,
            'user'      => Auth::user()->id,
        ];

        Notification::send($tujuan, new PushNotification($data));
    }
}
