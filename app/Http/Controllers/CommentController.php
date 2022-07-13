<?php
// selbst erstellter Code
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;

class CommentController extends Controller
{

    // Funktion, die einen neuen Kommentar erstellt für den übergebenen Task mit dem übergebenen Text
    // Route zu dieser Funktion ist auch durch Gast-Benutzer aufrufbar
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
