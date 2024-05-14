<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Store;
use App\Models\Region;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class EventController extends Controller
{

    public function allevent()
    {
        /*----------------- Event All Api Code ---------------*/


        $events_count = Event::select('events.*')->where('status', 'Active')->count();


        if ($events_count > 0) {
            $events = Event::select('name', 'location', 'learn_more', 'date', 'startTime', 'endTime', 'google_map_link', 'image')->where('status', 'Active')->get();

            $results = [];
            foreach ($events as $event) {
                $formattedDate = Carbon::parse($event->date)->format('l, F jS Y');
                $startTime = Carbon::parse($event->startTime)->format('g:i A');
                $endTime = Carbon::parse($event->endTime)->format('g:i A');


                $allData['date'] = $formattedDate;
                $allData['startTime'] = $startTime;
                $allData['endTime'] = $endTime;
                $allData['name'] = $event->name;
                $allData['location'] = $event->location;
                $allData['learn_more'] = $event->learn_more;
                $allData['location_link'] = $event->google_map_link;

                $allData['image'] = URL::to(Storage::url($event->image));
                /*$allData['image'] = Storage::url($event->image);*/


                array_push($results, $allData);
            }

            return response()->json(['status' => 200, 'data' => $results], 200);
        } else {
            return response()->json(['status' => 500, 'message' => "Data not Found"]);
        }
        /*---------------- End  Event Api Code ---------------------*/
    }



    public function index(Request $request)
    {
        // $events = Event::paginate(8);

        if ($request->ajax()) {
            $events = Event::select('events.*')->get();




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

        return view('event.event-list');
    }

    public function create()
    {
        $data['form_title'] = "Create Event";
        $store = Store::all();

        return view('event.event-create-edit', compact('store', 'data'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'learn_more' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required',
            'status' => 'required|in:Active,In Active',
        ]);
        $event = new Event();
        $event->name = $request->name;
        $event->location = $request->location;
        $event->learn_more = $request->learn_more;
        $event->google_map_link = $request->google_map_link;
        $event->startTime = $request->startTime;
        $event->endTime = $request->endTime;
        $event->date = $request->date;
        $event->description = $request->description;
        $event->status = $request->status;

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $filepath = "image/" . $filename;
            $path = Storage::disk("public")->putFileAs(
                "image",
                $request->file("image"),
                $filename
            );
            $event->image = $filepath;
        }

        $event->save();

        return redirect()->route('event.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        // dd('Heyy edit mre');
        $data['form_title'] = "Edit Event";

        $store = Store::all();

        return view('event.event-create-edit', compact('event', 'store', 'data'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required',
            'location' => 'required',
            'learn_more' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
        ], [
            'name.required' => 'Name is required.',
            'location.required' => 'Location is required.',
            'learn_more.required' => 'Learn More is required.',
            'startTime.required' => 'Start Time is required.',
            'endTime.required' => 'End Time is required.',
            'image.required' => 'Image is required.',
            'image.image' => 'Only image files are allowed.',
            'image.mimes' => 'Allowed image formats are: jpeg, png, jpg, gif.',
            'image.max' => 'Image size must not exceed 2MB.',
            'date.required' => 'Date is required.',
        ]);

        $updatedArray = [
            'name' => $request->name,
            'location' => $request->location,
            'learn_more' => $request->learn_more,
            'google_map_link' => $request->google_map_link,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
            'date' => $request->date,
            'description' => $request->description,
            'status' => $request->status,
        ];

        if ($request->hasFile('image')) {
            $filename = $request->image->getClientOriginalExtension();
            $filepath = 'image/' . time() . '.' . $filename;
            $path = Storage::disk('public')->put($filepath, file_get_contents($request->image));
            $updatedArray['image'] = $filepath;
        } elseif (!empty($request->old_image)) {
            $updatedArray['image'] = $request->old_image;
        }

        $event->update($updatedArray);

        return redirect()->route('event.index')->with('success', 'Event updated successfully.');
    }


    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('event.index')->with('success', 'Event deleted successfully.');
    }

}
