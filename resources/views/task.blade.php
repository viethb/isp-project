<div class="task-small {{ isset($task->due_date) && $task->isDueIn() < 7 ? 'is-due' : ''  }}"
     onclick="showOverlayContainer({{ $task->id }})">
    <div class="task-small-id">
        #{{ $task->id }}
    </div>
    <div class="task-small-title">
        {{ $task->title }}
    </div>
    <div class="task-small-priority">
        {!! $task->getPrioritySymbol() !!}
    </div>
{{--    @if(isset($task->assignee_id))--}}
{{--        <img class="h-6 w-6 rounded-full object-cover"--}}
{{--             src="{{ $task->assignee->profile_photo_url }}"--}}
{{--             alt="{{ $task->assignee->name }}"/>--}}
{{--    @endif--}}
    <div class="task-small-due-date">
        {{ $task->due_date }}
    </div>
</div>

<div id="{{ $task->id }}" class="overlay-container update-task">
    <div class="overlay">
        <form id="updateTaskForm" method="POST" action="{{route('updateTask', ['key' => $key])}}">
        <div class="overlay-section">
            <span>#{{ $task->id }}</span>


                @csrf
                <input type="hidden" name="id" value="{{ $task->id }}">

                <label for="title">Aufgabentitel</label>
                <input type="text" name="title" value="{{ $task->title }}" required>
                <br>
                <label for="description">Beschreibung</label>
                <textarea name="description" rows="5" maxlength="2500">{{ $task->description }}</textarea>

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
                        <input type="text" name="assignee" value="{{ $task->assignee ?? '' }}">
                    </div>
                </div>
                <div class="overlay-container-segment">
                    <div>
                        <label for="dueDate">Fälligkeitsdatum</label>
                        <input type="date" name="dueDate" value="{{ $task->due_date }}">
                    </div>
                    <div>
                        <label for="type">Typ</label>
                        <select name="type">
                            <option value="null" selected>leer</option>
                            <option value="Planung">Planung</option>
                            <option value="Neues Feature">Neues Feature</option>
                            <option value="Bug">Bug</option>
                            <option value="Verbesserung">Verbesserung</option>
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
                <div class="button-container">
                    <button type="reset" onclick="hideOverlayContainer({{ $task->id }})" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                        Abbrechen
                    </button>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Speichern
                    </button>
                </div>

        </div>

        <div class="overlay-section">
            Kommentare
            <div class="overlay-container-segment">
                @foreach($task->comments()->get() as $comment)
                    <div>
                        {{ $comment->created_at }}
                        {{ $comment->text }}
                    </div>
                @endforeach
            </div>

            <label for="comment">Kommentar</label>
            <textarea name="comment" rows="2" maxlength="2500"></textarea>
        </div>
        </form>
    </div>

</div>
