<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notis = Notification::orderBy('id', 'desc')->get();
        return view('admin.notifications.index', compact('notis'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'to_user' => 'required|in:1,2',
                'is_published' => 'required|in:0,1',
            ]);

            Notification::create([
                'title' => $request->title,
                'description' => $request->description,
                'to_user' => $request->to_user,
                'is_published' => $request->is_published,
            ]);
            return back()->with('success', 'Data store successfully!');
        } catch (\Exception $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Notification::find($id)->delete();
            return back()->with('success', 'Data deleted successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function changeStatus($id)
    {
        $noti = Notification::find($id);
        $status = $noti->is_published == 1 ? 0 : 1;
        $message = $status == 1 ? 'Published Successfully' : 'Published off Successfully';
        $noti->is_published = $status;
        $noti->save();
        return response()->json($message);
    }
}
