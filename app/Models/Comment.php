<?php
// selbst erstellter Code
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'task_id',
        'text'
    ];

    // Jeder Kommentar gehört zu genau einem Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
