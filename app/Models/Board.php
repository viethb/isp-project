<?php
// selbst erstellter Code
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{

    protected $fillable = [
        'creator_id',
        'title',
        'description',
        'key',
    ];

    // Jedes Board gehört zu genau einem User
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    // Zu einem Board gehören keine bis viele Tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Die Methode gibt einen Boolean zurück, ob das Board dem User mit der übergebenen ID gehört
    public function isUserOwner(int $id): bool {
        return $id === $this->creator_id;
    }
}
