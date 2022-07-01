<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Board;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        //
    }

    public function addTask (string $key, Request $request)
    {
        try {
            $board = Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exeption for key '.$key.' : '.$e);
            return redirect()->back();
        }

        $title = $request->input('title');
        $description = $request->input('description');
        $assignee = $request->input('assignee');
        $dueDate = $request->input('dueDate');
        $priority = $request->input('priority');
        $type = $request->input('type');

        $task = Task::create([
            'title' => $title,
            'board_id' => $board->id,
        ]);

        if($description) {
            $task->description = $description;
        }
        if($assignee !== "null") {
            $task->assignee = $assignee;
        }
        if($dueDate) {
            $task->due_date = $dueDate;
        }
        if($priority) {
            $task->priority = $priority;
        }
        if($type!== "null") {
            $task->type = $type;
        }

        $task->save();

        return redirect()->back();
    }

    public function updateTask (string $key, Request $request)
    {
        try {
            $board = Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exeption for key '.$key.' : '.$e);
            return redirect()->back();
        }

        $id = $request->input('id');
        $title = $request->input('title');
        $description = $request->input('description');
        $status = $request->input('status');
        $assignee = $request->input('assignee');
        $dueDate = $request->input('dueDate');
        $priority = $request->input('priority');
        $type = $request->input('type');
        $commentText =$request->input('comment');

        $task = Task::find($id);

        if($task) {
            $task->title = $title;
            $task->description = $description;

            if($assignee == "null") {
                $task->assignee = null;
            } else {
                $task->assignee = $assignee;
            }
            if($type == "null") {
                $task->type = null;
            } else {
                $task->type = $type;
            }
            $task->due_date = $dueDate;
            $task->priority = $priority;
            $task->status = $status;

            $task->save();

            if($commentText){
                $newComment = Comment::create([
                    'text' => $commentText,
                    'task_id' => $id
                ]);
                $newComment->save();
            }
        }

        return redirect()->back();
    }
}
