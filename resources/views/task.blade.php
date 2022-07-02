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
        @auth
            <form id="editTaskForm" method="POST" action="{{route('editTask', ['key' => $key])}}">
        @endauth
        @guest
            <form id="editTaskForm" method="POST" action="{{route('updateTask', ['key' => $key])}}">
        @endguest


        <div class="overlay-section">
            <span>#{{ $task->id }}</span>
                @csrf
                <input type="hidden" name="id" value="{{ $task->id }}">

                @auth
                    <input type="text" name="title" value="{{ $task->title }}" required>
                    <textarea name="description" rows="5" maxlength="2500">{{ $task->description }}</textarea>
                @endauth
                @guest
                    <h3>{{ $task-> title }}</h3>
                    <textarea rows="5" readonly>{{ $task->description }}</textarea>
                @endguest

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
                @auth
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
                @endauth
                @guest
                    <div class="overlay-container-segment">
                        <div>
                            <label for="dueDate">Fälligkeitsdatum</label>
                            <p id="dueDate">{{ $task->due_date }}</p>
                        </div>
                        <div>
                            <label for="type">Typ</label>
                            <p id="type">{{ $task->type ?? '' }}</p>
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
                @endguest
                <div class="button-container">
                    <button type="reset" onclick="hideOverlayContainer({{ $task->id }})" class="button-secondary">
                        Abbrechen
                    </button>
                    <button type="submit">
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
