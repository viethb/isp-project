<?php
// selbst erstellter Code
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    // Funktion, die einen neuen Task erstellt für das Board mit dem übergebenen Key
    // Sämtliche weitere relevanten Daten werden als POST-Request übergeben
    // Route zu dieser Funktion nur durch authentifizierte Nutzer aufrufbar
    public function addTask (string $key, Request $request)
    {
        // Task wird nur erstellt, wenn für den übergebenen Key ein Board gefunden wird
        // und wenn das Board dem aktuell angemeldeten Benutzer gehört
        try {
            $board = Board::where('key', $key)->firstOrFail();
            if(!$board->isUserOwner(Auth::id())){
                throw new \Exception('User with ID \''.Auth::id().'\' is not the owner of this board');
            }
        }
        catch(\Exception $e) {
            error_log('---> Exeption for Board '.$key.' : '.$e);
            return redirect()->back();
        }

        $title = $request->input('title');
        $description = $request->input('description');
        $assignee = $request->input('assignee');
        $dueDate = $request->input('dueDate');
        $priority = $request->input('priority');
        $type = $request->input('type');

        // die Nummer des neuen Tasks entspricht der Anzahl aller Tasks auf dem Board (inklusive dem neuen)
        $number = $board->tasks()->count() + 1;

        $task = Task::create([
            'title' => $title,
            'board_id' => $board->id,
            'task_number' => $number,
        ]);

        // folgende Felder werden nur gesetzt, wenn im Create-Dialog Werte eingetragen wurden, die vom Default abweichen
        if($description) {
            $task->description = $description;
        }
        if($assignee !== "null") {
            $task->assignee = $assignee;
        }
        if($dueDate) {
            $task->due_date = $dueDate;
        }
        if($priority !== 1) {
            $task->priority = $priority;
        }
        if($type!== "null") {
            $task->type = $type;
        }

        $task->save();

        // Redirect zur vorherigen Route; wenn per Buttons navigiert wurde, ist das die Board-Seite
        return redirect()->back();
    }

    // Funktion, die die Werte eines bestehenden Tasks anpasst und einen Kommentar hinzufügen kann
    // Sämtliche relevanten Daten werden als POST-Request übergeben
    // Route zu dieser Funktion nur durch authentifizierte Nutzer aufrufbar
    public function editTask (string $key, Request $request)
    {
        // Task wird nur editiert, wenn für den übergebenen Key ein Board gefunden wird
        // und wenn das Board dem aktuell angemeldeten Benutzer gehört
        try {
            $board = Board::where('key', $key)->firstOrFail();
            if(!$board->isUserOwner(Auth::id())){
                throw new \Exception('User with ID \''.Auth::id().'\' is not the owner of this board');
            }
        }
        catch(\Exception $e) {
            error_log('---> Exeption for Board '.$key.' : '.$e);
            return redirect()->back();
        }

        // die ID des Tasks wurde als hidden Input-Wert im Formular übergeben
        $id = $request->input('id');
        $title = $request->input('title');
        $description = $request->input('description');
        $status = $request->input('status');
        $assignee = $request->input('assignee');
        $dueDate = $request->input('dueDate');
        $priority = $request->input('priority');
        $type = $request->input('type');
        $commentText = $request->input('comment');

        $task = Task::find($id);

        // Überprüfung, ob Task gefunden wurde
        // falls nicht, erfolgt keine Fehlermeldung, sondern nur ein Redirect
        if($task) {
            $task->title = $title;
            $task->description = $description;
            $task->assignee = $assignee;

            if($type == "null") {
                $task->type = null;
            } else {
                $task->type = $type;
            }
            $task->due_date = $dueDate;
            $task->priority = $priority;
            $task->status = $status;

            $task->save();

            // wenn im Kommentar-Feld etwas eingetragen wurde, wird ein neuer Kommentar für den Task erstellt
            if($commentText){
                $newComment = Comment::create([
                    'text' => $commentText,
                    'task_id' => $id
                ]);
                $newComment->save();
            }
        }

        // Redirect zur vorherigen Route; wenn per Buttons navigiert wurde, ist das die Board-Seite
        return redirect()->back();
    }

    // Funktion, die die Werte 'Status' und 'Bearbeiter' eines bestehenden Tasks anpasst und einen Kommentar hinzufügen kann
    // Sämtliche relevanten Daten werden als POST-Request übergeben
    // Route zu dieser Funktion ist auch durch Gast-Benutzer aufrufbar
    public function updateTask(string $key, Request $request) {
        // Task wird nur editiert, wenn für den übergebenen Key ein Board gefunden wird (Überprüfung eigentlich nicht zwingend notwendig)
        try {
            Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exeption: Key not found! Key: '.$key.' : '.$e);
            return redirect()->back();
        }

        // die ID des Tasks wurde als hidden Input-Wert im Formular übergeben
        $id = $request->input('id');
        $status = $request->input('status');
        $assignee = $request->input('assignee');
        $commentText = $request->input('comment');

        $task = Task::find($id);

        // Überprüfung, ob Task gefunden wurde
        // falls nicht, erfolgt keine Fehlermeldung, sondern nur ein Redirect
        if($task) {
            $task->assignee = $assignee;
            $task->status = $status;

            $task->save();

            // wenn im Kommentar-Feld etwas eingetragen wurde, wird ein neuer Kommentar für den Task erstellt
            if($commentText){
                $newComment = Comment::create([
                    'text' => $commentText,
                    'task_id' => $id
                ]);
                $newComment->save();
            }
        }

        // Redirect zur vorherigen Route; wenn per Buttons navigiert wurde, ist das die Board-Seite
        return redirect()->back();
    }

    // Funktion, die den Status eines bestehenden Tasks anpasst, wenn der Task per Drag and Drop verschoben wurde
    // Route zu dieser Funktion ist auch durch Gast-Benutzer aufrufbar
    public function updateStatus (string $key, int $id, int $status) {
        $board = Board::where('key', $key)->first();
        $task = Task::find($id);

        // Überprüfung, ob Board und Task gefunden wurden und der neue Status sich vom alten unterscheidet
        // nur dann wird eine Änderung in der Datenbank vorgenommen
        if ( $board && $task && $task->status !== $status){
            $task->status = $status;
            $task->save();
        }

        // Redirect zur vorherigen Route; wenn per Buttons navigiert wurde, ist das die Board-Seite
        return redirect()->back();
    }
}
