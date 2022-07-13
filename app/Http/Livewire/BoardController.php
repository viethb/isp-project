<?php
// selbst erstellter Code, bis auf den Inhalt von generateKey
namespace App\Http\Livewire;

use App\Models\Board;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BoardController extends Component
{
    public function render()
    {
        return view('livewire.board');
    }

    // Funktion, die ein neues Board erstellt für den gerade angemeldeten Benutzer
    // Sämtliche relevanten Daten werden als POST-Request übergeben
    // Route zu dieser Funktion ist nur durch authentifizierte Nutzer aufrufbar
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
        // Redirect zur vorherigen Route; wenn per Buttons navigiert wurde, ist das die Welcome-Seite
        return redirect()->back();
    }

    // Funktion, die einen noch nicht für andere Boards vergebenen 16-stelligen Board-Key generiert
    // String-Generator von Stackoverflow übernommen: https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
    private function generateKey(
        int $length = 16,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        // Key wird so lange neu generiert, bis ein Key erstellt wird, der noch zu keinem Board gehört
        do {
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $pieces []= $keyspace[random_int(0, $max)];
            }
            $key = implode('', $pieces);
        } while($this->checkIfKeyExists($key));

        return $key;
    }

    // Funktion, die prüft, ob bereits ein Board mit dem übergebenen Key existiert
    private function checkIfKeyExists(string $key){
        $board = Board::where('key', $key)->first();
        return $board !== null;
    }

    // Funktion, die die Werte des Boards mit dem übergebenen Key anpasst
    // Sämtliche weiteren relevanten Daten werden als POST-Request übergeben
    // Route zu dieser Funktion nur durch authentifizierte Nutzer aufrufbar
    public function updateBoard(Request $request, string $key) {
        // Änderungen werden nur vorgenommen, wenn für den übergebenen Key ein Board gefunden wird
        // und der aktuell angemeldete Benutzer der Besitzer des Boards ist
        try {
            $board = Board::where('key', $key)->firstOrFail();
            if(!$board->isUserOwner(Auth::id())){
                throw new \Exception('User with ID \''.Auth::id().'\' is not the owner of this board');
            }
        }
        catch(\Exception $e) {
            error_log('---> Exception ofr Board '.$key.': '.$e);
            return redirect()->route('root');
        }

        $title = $request->input('title');
        $description = $request->input('description');

        $board->title = $title;
        $board->description = $description;

        $board->save();
        // Redirect zur vorherigen Route; wenn per Buttons navigiert wurde, ist das die Board-Seite
        return redirect()->back();
    }

   // Funktion, die das Board mit dem übergebenen Key anzeigt
    public function show(Request $request, string $key = null) {
        // wenn in der Route kein Key angegeben wurde, wird der Key aus dem GET-Request ausgelesen
        // die Route wird dann erneut mit diesem Key aufgerufen
        // dieser Fall tritt ein, wenn auf der Root-Seite ein Board-Key eingegeben wird, um zu einem Board zu navigieren
        // (im Gegensatz dazu, dass ein angemeldeter Benutzer einen Board-Link-Button auf seiner Welcome-Seite anklickt,
        // dann wird der Key direkt mitgegeben)
        if (!isset($key))
        {
            $key = $request->input('board-key');
            return redirect()->route('showBoard', ['key'=> $key]);
        }

        // Überprüfung, ob für den übergebenen Key ein Board gefunden wird
        // falls nicht, wird zur Root-Seite zurück navigiert
        try {
            $board = Board::where('key', $key)->firstOrFail();
        }
        catch(\Exception $e) {
            error_log('---> Exception: '.$e);
            return redirect()->route('root');
        }

        // wenn ein Board gefunden wurde, wird dessen View aufgerufen
        return view("livewire.board", ['board' => $board]);
    }

    // Funktion, die das Board mit dem übergebenen Key löscht
    // Route zu dieser Funktion nur durch authentifizierte Nutzer aufrufbar
    public function deleteBoard(string $key) {
        // Task wird nur erstellt, wenn für den übergebenen Key ein Board gefunden wird
        // und das Board dem aktuell angemeldeten Benutzer gehört
        // ansonsten wird direkt zum Board zurück navigiert
        try {
            $board = Board::where('key', $key)->firstOrFail();
            if(!$board->isUserOwner(Auth::id())){
                throw new \Exception('User with ID \''.Auth::id().'\' is not the owner of this board');
            }
        }
        catch(\Exception $e) {
            error_log('---> Exception for Board '.$key.': '.$e);
            return redirect()->back();
        }
        // besser wäre hier, direkt bei der Tabellen-Erstellung ein ->onDelete('cascade') für den Foreign-Key anzugeben
        // aus Zeitgründen als Workaround implementiert
        $board->tasks()->each(function ($task, $key) {
            $task->comments()->delete();
        });
        $board->tasks()->delete();
        $board->delete();

        // Nach dem Löschen wird zur Welcome-Seite zurück navigiert
        return redirect()->route('livewire-welcome');
    }
}
