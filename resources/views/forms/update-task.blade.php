{{-- selbst erstellter Code --}}
<div id="{{ $task->id }}" class="overlay-container update-task">
    <div class="overlay">
        @if(Auth::user() && $board->isUserOwner(Auth::id()))
            <form id="editTaskForm" method="POST" action="{{route('editTask', ['key' => $key])}}">
        @else
            <form id="editTaskForm" method="POST" action="{{route('updateTask', ['key' => $key])}}">
        @endif

            <div class="overlay-section">
                <span>#{{ $task->task_number }}</span>
                @csrf
                <input type="hidden" name="id" value="{{ $task->id }}">

                @if(Auth::user() && $board->isUserOwner(Auth::id()))
                    <input type="text" name="title" value="{{ $task->title }}" maxlength="255" required>
                    <textarea name="description" rows="5" maxlength="1000"
                              placeholder="Hier könnte eine tolle Beschreibung stehen">{{ $task->description }}</textarea>
                @else
                    <h3>{{ $task-> title }}</h3>
                    <textarea rows="5" readonly placeholder="Hier könnte eine tolle Beschreibung stehen">{{ $task->description }}</textarea>
                @endif

                <div class="overlay-container-segment" id="update-task-first-segment">
                    <div>
                        <div>
                            <label for="status">Status</label>
                        </div>
                        <select name="status">
                            <option value="0" {{ $task->status == 0 ? 'selected' : '' }}>Offen</option>
                            <option value="1" {{ $task->status == 1 ? 'selected' : '' }}>In Arbeit</option>
                            <option value="2" {{ $task->status == 2 ? 'selected' : '' }}>Abgeschlossen</option>
                            <option value="3" {{ $task->status == 3 ? 'selected' : '' }}>Vergangen</option>
                        </select>
                    </div>
                    <div>
                        <div>
                            <label for="assignee">Bearbeiter</label>
                        </div>
                        <input type="text" name="assignee" value="{{ $task->assignee ?? '' }}" maxlength="50">
                    </div>
                </div>
                @if(Auth::user() && $board->isUserOwner(Auth::id()))
                    <div class="overlay-container-segment">
                        <div>
                            <label for="dueDate">Fälligkeitsdatum</label>
                            <input type="date" name="dueDate" value="{{ $task->due_date }}">
                        </div>
                        <div>
                            <label for="type">Typ</label>
                            <select name="type">
                                <option
                                    value="null"
                                    {{ $task->type == "null" ? 'selected' : '' }}>
                                    leer
                                </option>
                                <option
                                    value="Planung"
                                    {{ $task->type == "Planung" ? 'selected' : '' }}>
                                    Planung
                                </option>
                                <option
                                    value="Neues Feature"
                                    {{ $task->type == "Neues Feature" ? 'selected' : '' }}>
                                    Neues Feature
                                </option>
                                <option
                                    value="Bug"
                                    {{ $task->type == "Bug" ? 'selected' : '' }}>
                                    Bug
                                </option>
                                <option
                                    value="Verbesserung"
                                    {{ $task->type == "Verbesserung" ? 'selected' : '' }}>
                                    Verbesserung
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="overlay-container-segment">
                        <div>
                            <label for="priority">Priorität</label>
                            <select name="priority">
                                <option value="0" {{ $task->priority == 0 ? 'selected' : '' }}>niedrig</option>
                                <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}>mittel</option>
                                <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>hoch</option>
                                <option value="3" {{ $task->priority == 3 ? 'selected' : '' }}>sehr hoch</option>
                            </select>
                        </div>
                        <div>
                            <label for="created_at">Erstellungsdatum</label>
                            <p>{{ $task->created_at }}</p>
                        </div>
                    </div>
                @else
                    <div class="overlay-container-segment">
                        <div>
                            <label for="dueDate">Fälligkeitsdatum</label>
                            <p id="dueDate">{{ $task->due_date ?? 'keines' }}</p>
                        </div>
                        <div>
                            <label for="type">Typ</label>
                            <p id="type">{{ $task->type ?? 'Standard' }}</p>
                        </div>
                    </div>
                    <div class="overlay-container-segment">
                        <div>
                            <label for="priority">Priorität</label>
                            <p id="priority">{{ $task->getPriority() }}</p>
                        </div>
                        <div>
                            <label for="created_at">Erstellungsdatum</label>
                            <p>{{ $task->created_at }}</p>
                        </div>
                    </div>
                @endif
                <div class="button-container">
                    <button type="reset" onclick="hideOverlayContainer({{ $task->id }})" class="button-secondary">
                        Abbrechen
                    </button>
                    <button type="submit" class="button-small">
                        Speichern
                    </button>
                </div>

            </div>

            <div class="overlay-section">
                <h3>Kommentare</h3>
                <div id="comment-section">
                    @foreach($task->comments()->get() as $comment)
                        <div>
                            <span class="comment-timestamp">{{ $comment->created_at }}</span>
                            <span class="comment-text">{{ $comment->text }}</span>
                        </div>
                    @endforeach
                </div>

                <div id="overlay-new-comment-segment">
                    <label for="comment">Kommentar</label>
                    <textarea name="comment" rows="4" maxlength="1000"></textarea>
                </div>
            </div>
        </form>
    </div> {{-- Element ist geschlossen, Fehler wird wegen des "doppelten" form-opening-Tags angezeigt (einmal für auth, einmal für guest)  --}}

</div>
