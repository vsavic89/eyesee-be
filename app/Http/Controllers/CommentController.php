<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // $this->middleware('auth', ['only' => ['store']]);   
    }

    public function MarkAsVisible(Request $request)
    {                
        $comment = Comment::findOrFail($request['commentId']);
        $thread = Thread::findOrFail($comment->thread_id);        
        if($comment->user_id === $thread->user_id)
        {
            $comment->visible = true;
            $comment->save();

            $s = '<h1>Comment is now visible!</h1>';
            return view('welcome', compact('s'));
        }else{
            return response()->json([
                'message' => 'Can not set this comment to visible because current user is not the same as one who created the thread.'
            ]);
        }
    }

    public function index()
    {
        
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
        if(auth()->check())
        {
            $comment = new Comment();
            $comment->content = $request['content'];
            $comment->parent_comment_id = $request['parent_comment_id'];
            $comment->thread_id = $request['thread_id'];
            $comment->user_id = auth()->getUser()->id;
            $comment->save();

            $s = '<h1>Comment is added!</h1>';
            return view('welcome', compact('s'));
        }else{
            return response()->json([
                'message' => 'Can not add comment. User is not logged in.'
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
        $comment = Comment::findOrFail($id);

        return $comment;
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
        $comment = Comment::findOrFail($id);        
        $comment->content = $request['content'];                   
        $comment->save();

        $s = '<h1>Comment is edited!</h1>';
        return view('welcome', compact('s'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
    }
}
