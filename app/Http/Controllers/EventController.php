<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;
class EventController extends Controller
{
    public function index(){
        $start = $this->formatDate(request('start'));
        $end = $this->formatDate(request('end'));

        $events = Event::select('id', 'title', 'date')->whereBetween('date', [$start, $end])->get();
        $newArr = [];
        foreach($events as $item){
            $newItem["id"] = $item["id"];
            $newItem["title"] = $item["title"];
            $newItem["start"] = $item["date"];
            $newArr[] = $newItem;
        }

        return response()->json($newArr);
    }
    public function formatDate($date){
        return str_replace('T00:00:00+09:00', '', $date);
    }
    public function show(Event $event){
        return  response()->json($event);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $event = new Event();
        $event->date = $data['date'];
        $event->title = $data['title'];
        $event->save();

        return response()->json(['event_id' => $event->event_id ]);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }
    public function update(Event $event, Request $request){
        $data = $request->all();
        if($data['title']){
         $event->title = $data['title'];
        }
        if($data['date']){
            $event->date = $data['date'];
        }
        $event->save();
        return response()->json($event, 200);
    }
}
