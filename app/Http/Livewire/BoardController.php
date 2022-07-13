<?php

namespace App\Http\Livewire;

use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BoardController extends Component
{
    public function render()
    {
        return view('livewire.board');
    }

    public function addBoard(Request $request) {
        $title = $request->input('title');
        $description = $request->input('description');
        $key = $this->generateKey();

        $board = Board::create([
            'title' => $title,
            'description' => $description,
            'key' => $key,
            'creator_id' => Auth::id(),
        ]);

        $board->save();
        return redirect()->back();
    }

    // String-Generator von Stackoverflow übernommen: https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
    private function generateKey(
        int $length = 16,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        do {
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
            }
            $key = implode('', $pieces);
            error_log('--> $key inside do-while: '.$key);
        } while($this->checkIfKeyExists($key));

        error_log('--> $key after do-while: '.$key);
        return $key;
    }

    private function checkIfKeyExists(string $key){
        $board = Board::where('key', $key)->first();
        return $board !== null;
    }

    public function updateBoard(Request $request, string $key) {
        try {
            error_log('----> update-Board-Function, key: '.$key);
            $board = Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exception: '.$e);
            return redirect()->route('root');
        }

        $title = $request->input('title');
        $description = $request->input('description');

        $board->title = $title;
        $board->description = $description;

        $board->save();
        return redirect()->back();
    }

    public function show(Request $request, string $key = null) {
        if (!isset($key))
        {
            $key = $request->input('board-key');
            return redirect()->route('showBoard', ['key'=> $key]);
        }

        try {
            $board = Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exception: '.$e);
            return redirect()->route('root');
        }


        error_log('---> show board, board_id: '.$board->id);

        return view("livewire.board", ['board' => $board]);
    }

    public function deleteBoard(string $key) {
        try {
            $board = Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exception: Key not found! Key: '.$key.' : '.$e);
            return redirect()->back();
        }
        // besser wäre hier, direkt bei der Tabellen-Erstellung ein ->onDelete('cascade') für den Foreign-Key anzugeben
        $board->tasks()->each(function ($task, $key) {
            $task->comments()->delete();
        });
        $board->tasks()->delete();
        $board->delete();
        return redirect()->route('livewire-welcome');
    }
}
