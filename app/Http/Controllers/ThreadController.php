<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use DateTime;
use App\APIParameters;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $baseURL;

    public function __construct()
    {
        //  $this->middleware('auth', ['only' => ['store']]);   
        $api = new APIParameters();
        $this->baseURL = $api->getBaseAPIURL();
    }

    public function index()
    {                
        $threads = Thread::all();                    

        $s = '<h1><u>Threads</u></h1>';
        foreach($threads as $thread)
        {
            $s .= '<br /><p><a href="'.$this->baseURL.'threads/'.$thread->id.'"/>'.$thread->title.'</p>';
        }        
        return view('welcome', compact('s'));
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
            $thread->user_id = auth()->getUser()->id;
            $thread->save();

            $s = '<h1>Thread created!</h1>';
            return view('welcome', compact('s'));
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

        $s = '<h1><u>Thread</u></h1>';
        $s .= '<br /><p>Thread id: ' . $thread->id . '</p>';
        $s .= '<br /><p>Thread title: '. $thread->title . '</p>';
        $s .= '<br /><p>Thread content: '. $thread->content . '</p>';
        $s .= '<br /><p>Thread title: '. $thread->title . '</p>';
        $s .= '<br /><h1><u>Comments</u></h1>';
        foreach($thread->comments as $comment)
        {
            $s .= '<br />Comment id: '.$comment->id;
            $s .= '<br />Comment content: '.$comment->content;
        }
        
        return view('welcome', compact('s'));
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

                    $s = '<h1>Thread successfully updated!</h1>';

                    return view('welcome', compact('s'));
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
