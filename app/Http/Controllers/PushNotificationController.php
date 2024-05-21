<?php

namespace App\Http\Controllers;

// use App\Http\Requests\Company\CreateCompanyRequest;
// use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Appcustomers;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\ProductService;
use Spatie\Permission\Models\Permission;
use App\Helpers\Toast;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PushNotificationController extends Controller
{


    public function index()
    {
        $users = User::get();
        return view('notifications.notification-list', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'notification_title' => 'required|string',
                'notification_message_body' => 'required|string',
                'selectedMembers' => 'array',
            ]);
            $data = [
                'notification_title' => $validatedData['notification_title'],
                'notification_message_body' => $validatedData['notification_message_body'],
                'sendToAllMembers' => $request->has('sendToAllMembers'),
                'notification_type' => $request['notification_type'],
                // 'read_status' => $request->has('read_status'),
            ];
            if ($request->has('sendToAllMembers')) {
                $users = User::all();
                foreach ($users as $user) {
                    $data['user_id'] = $user->id;
                    $this->sendPushNotification($data, $user->notification_token);
                    PushNotification::create($data);
                }
            }

            if ($request->has('selectedMembers')) {
                $selectedUsers = $validatedData['selectedMembers'];
                $userData = User::whereIn('id', $selectedUsers)->get();
                foreach ($userData as $user) {
                    $data['user_id'] = $user->id;
                    // $this->sendPushNotification($data, $user->notification_token);
                    PushNotification::create($data);
                }
            }

            return redirect()->route("pushNotification.index")
                ->with('success', 'You have successfully sent notifications.');
        } catch (\Exception $e) {
            Log::error('Error sending notifications: ' . $e->getMessage());

            return redirect()->route('pushNotification.index')
                ->with('error', "Error sending notifications: {$e->getMessage()}");
        }
    }

    public function sendPushNotification($data, $deviceToken)
    {
        $appAuth = Firebase::auth();
        try {
            $messaging = Firebase::messaging();
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification(Notification::create($data['notification_title'], $data['notification_message_body']))
                ->withData(['custom_key' => 'custom_value']);

            $messaging->send($message);

            Log::info('Push notification sent successfully to device with token: ' . $deviceToken);
        } catch (\Exception $e) {
            Log::error('Error sending push notification: ' . $e->getMessage());

        }
    }


    public function list(Request $request)
    {

        // $events = Event::paginate(8);

        if ($request->ajax()) {
            $events = PushNotification::select('push_notifications.*')->orderBy('id','desc')->get();




            return Datatables::of($events)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {

                    $imageurl = Storage::url($row->image);
                    $image = '<img src="' . $imageurl . '" width="150"  />';
                    return $image;
                })

                ->addColumn('action', function ($row) {

                    $edit_url = route('event.edit', $row->id);
                    $delete_url = route('event.destroy', $row->id);
                    $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . $delete_url . '" class="btn btn-danger" onclick="return deleteConfirm()"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }

        return view('notifications.push-notification-listing');
    }

}
