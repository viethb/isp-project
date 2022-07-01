<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        //
    }

    public function addComment (string $task_id, string $text)
    {
        $task = Task::find($task_id)->firstOrFail();
        if($task === null) {
            error_log('---> Task not found, ID: '.$task_id);
        }
        else {
            $comment = Comment::create([
                'text' => $text,
                'task_id' => $task_id,
            ]);

            $comment->save();
        }
    }
}
