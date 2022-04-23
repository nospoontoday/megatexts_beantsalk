<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param \Illuminate\Http\Request $request to handle pagination
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $activities = $user->actions->sortByDesc('updated_at');

        $data = $this->activitiesFormatter($user, $activities);

        return view('auth.activities.index', compact('user', 'data'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id User's id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $activities = $user->actions->sortByDesc('updated_at');

        $activitiesFormatted = $this->activitiesFormatter($user, $activities);

        return view('auth.activities.show', compact('user', 'activitiesFormatted'));
    }

    private function activitiesFormatter($user, $activities): array
    {
        $data = [];

        foreach($activities as $activity) {

            $data[] = $activity->description . ' ' . $activity->log_name . ' with ID# ' . $activity->subject_id . ' ' . $activity->updated_at->diffForHumans();
        }
    
        return $data;
    }
}
