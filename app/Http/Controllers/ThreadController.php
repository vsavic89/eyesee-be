<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //  $this->middleware('auth', ['only' => ['store']]);   
    }

    public function index()
    {        
        $threads = Thread::all();
        
        return $threads;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->check())
        {
            $thread = new Thread();
            $thread->title = $request['title'];
            $thread->content = $request['content'];
            $thread->user_id = $request['user_id'];
            $thread->save();

            return $thread;
        }else{
            return response()->json([
                'Can not add thread. User is not logged in.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $thread = Thread::findOrFail($id);

        return $thread;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
        if(auth()->check())
        {
            $userID = auth()->getUser()->id;
            $thread = Thread::findOrFail($id);        
            if($userID === $thread->user_id)
            {        
                $date1 = new DateTime($thread->created_at);
                $date2 = new DateTime();

                $diff = $date2->diff($date1);

                $hours = $diff->h;
                $hours = $hours + ($diff->days*24);

                if($hours <= 6)
                {
                    $thread->title = $request['title'];
                    $thread->content = $request['content'];                
                    $thread->save();
                }else{
                    return response()->json([
                        'message' => 'Can not edit thread. The creation time of the thread is more than 6h.'
                    ]);
                }
            }else{
                return response()->json([                
                    'message' => 'Can not edit thread. You are not the user who wrote the thread.'
                ]);
            }
        }else{
            return response()->json([
                'message' => 'User must be logged in order to edit the thread.'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $thread = Thread::findOrFail($id);
        $thread->delete();
    }
}
