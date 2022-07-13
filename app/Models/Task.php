<?php
// selbst erstellter Code
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class Task extends Model
{

    protected $fillable = [
        'board_id',
        'title',
        'description',
        'status',
        'type',
        'due_date',
        'priority',
        'assignee',
        'task_number'
    ];

    // Ein Task gehört zu genau einem Board
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    // Ein Task hat keine bis viele Kommentare
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Die Methode gibt eine String-Repräsentation des Task-Status zurück
    public function getStatus(): string
    {
        switch($this->status) {
            case 0:
                return "offen";
            case 1:
                return "in Arbeit";
            case 2:
                return "abgeschlossen";
            case 3:
                return "vergangen";
            // Es gibt genau diese vier Status, daher ist kein Default-Case nötig
        }
    }

    // Die Methode gibt eine String-Repräsentation der Task-Priorität zurück
    public function getPriority(): string
    {
        switch($this->priority) {
            case 0:
                return "niedrig";
            case 1:
                return "mittel";
            case 2:
                return "hoch";
            case 3:
                return "sehr hoch";
            // Es gibt genau diese vier Prioritäten, daher ist kein Default-Case nötig
        }
    }

    // Die Methode gibt ein Symbol zurück, das den Task-Status repräsentiert
    public function getPrioritySymbol()
    {
        switch($this->priority) {
            case 0: // niederig
                return '<svg fill="lightblue" stroke="blue">
                        <path stroke-width="1"
                                d="M 1 1 L 5 9 L 9 1 Z"/>
                    </svg>';
            case 1: // mittel
                return '<svg fill="lightgrey" stroke="grey">
                        <path stroke-width="1"
                              d="M 6, 6
        m -5, 0
        a 5,5 0 1,0 10,0
        a 5,5 0 1,0 -10,0"/>
                    </svg>';
            case 2: // hoch
                return '<svg fill="beige" stroke="orange">
                        <path stroke-width="1"
                              d="M 1 9 L 5 1 L 9 9 Z"/>
                    </svg>';
            case 3:
                return '<svg fill="pink" stroke="red">
                        <path stroke-width="1"
                              d="M 1 9 L 5 1 L 9 9 Z"/>
                    </svg>';
            // Es gibt genau diese vier Prioritäten, daher ist kein Default-Case nötig
        }
    }

    // Die Methode gibt zurück, wie viele Tage das Fälligkeitsdatum vom heutigen Tag entfernt ist (Zukunft oder Vergangenheit)
    public function isDueIn(): int
    {
        $today = date('Y-m-d');
        return (new DateTime($this->due_date))->diff(new DateTime($today))->days;

    }
}
