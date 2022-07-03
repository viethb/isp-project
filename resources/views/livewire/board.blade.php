<x-guest-layout>
<div>
<script src="{{ asset('js/kanban.js')}}" defer></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/kanban.css') }}" >
    @include('header-links')


    <div class="board-main-content">
        <div class="board-page-area" id="board-area-left">
            @auth
                <a href="{{ url('/welcome') }}" class="link-secondary">Zurück zu meinen Boards</a>
                <button class="button-small" onclick="showOverlayContainer('addTaskForm')">Neues Ticket</button>
            @endauth
            @guest
                <a href="{{ url('/') }}" class="link-secondary">Zu einem anderen Board</a>
            @endguest
            <div>
                Board-Key {{ $board->key }}
            </div>
            @auth
                <a onclick="showOverlayContainer('updateBoardForm')">
                   Board bearbeiten
                </a>
            @endauth
        </div>
        <div class="board-page-area" id="board-area-middle">
            <h2>{{ $board->title }}</h2>
            <div class="task-board-container border-slate-300">
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 0)' ondragover="allowDrop(event)">
                    {{-- Column 'Offen' --}}
                    <div class="bg-slate-50 border-b-2 border-slate-300">Offen</div>
                    @foreach($board->tasks()->where('status', 0)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Column 'In Arbeit' --}}
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 1)' ondragover="allowDrop(event)">
                    <div class="bg-slate-50 border-b-2 border-slate-300">In Arbeit</div>
                    @foreach($board->tasks()->where('status', 1)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Column 'Abgeschlossen' --}}
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 2)' ondragover="allowDrop(event)">
                    <div class="bg-slate-50 border-b-2 border-slate-300">Abgeschlossen</div>
                    @foreach($board->tasks()->where('status', 2)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Column 'Vergangen' --}}
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 3)' ondragover="allowDrop(event)">
                    <div class="bg-slate-50 border-b-2 border-slate-300">Vergangen</div>
                    @foreach($board->tasks()->where('status', 3)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>
            </div>
        </div>
        <div class="board-page-area" id="board-area-right">
            @guest
                Ersteller
                {{ $board->creator->name }}
            @endguest

            Board Beschreibung
            {{ $board->description }}
        </div>
    </div>

    {{-- Formular zum Erstellen eines neuen Tasks, nur sichtbar, wenn "Neuer Task" geklickt wird --}}
    <div id="addTaskForm" class="overlay-container">
        <div class="overlay">
            <form method="POST" action="{{route('addTask', ['key' => $board->key])}}">
                @csrf
                <div class="overlay-section">
                    <h3>Neuen Task erstellen</h3>
                    <label for="title">Titel</label>
                    <input type="text" name="title" required>

                    <label for="description">Beschreibung</label>
                    <textarea name="description" rows="5" maxlength="2500"></textarea>

                    <div class="overlay-container-segment">
                        <div>
                            <label for="dueDate">Fälligkeitsdatum</label>
                            <input type="date" name="dueDate">
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
                                <option value="0">niedrig</option>
                                <option value="1" selected>mittel</option>
                                <option value="2">hoch</option>
                                <option value="3">sehr hoch</option>
                            </select>
                        </div>
                        <div>
                            <label for="assignee">Bearbeiter</label>
                            <input type="text" name="assignee">
                        </div>
                    </div>
                    <div class="button-container">
                        <button type="reset" onclick="hideOverlayContainer('addTaskForm')" class="button-secondary">
                            Abbrechen
                        </button>
                        <button type="submit" class="button-small">
                            Speichern
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Formular zum Bearbeiten der Board-Informationen und Löschen des Boards, nur sichtbar, wenn "Board bearbeiten" geklickt wird --}}
    <div id="updateBoardForm" class="overlay-container">
        <div class="overlay">
            <form method="POST" action="{{ route('updateBoard', ['key' => $board->key]) }}">
                @csrf
                <div class="overlay-section">
                    <h3>Board bearbeiten</h3>
                    @auth
                        <a onclick="showOverlayContainer('deleteBoardForm')" class="link-secondary">Board löschen</a>
                    @endauth
                    <label for="title">Titel</label>
                    <input type="text" name="title" value="{{ $board->title }}" required>

                    <label for="description">Beschreibung</label>
                    <textarea name="description" rows="5" maxlength="2500">
                        {{ $board->description }}
                    </textarea>

                    <div class="button-container">
                        <button type="reset" onclick="hideOverlayContainer('updateBoardForm')" class="button-secondary">
                            Abbrechen
                        </button>
                        <button type="submit" class="button-small">
                            Speichern
                        </button>
                    </div>
                </div>
            </form>
            <!-- Board-Löschen-Button -->
        </div>
    </div>

    {{-- Sicherheitsabfrage vor dem Löschen des Boards, nur sichtbar, wenn "Board löschen" geklickt wurde --}}
    <div id="deleteBoardForm" class="overlay-container">
        <div class="overlay">
            <div class="overlay-section">
                <h3>Board löschen</h3>
                <p>Soll das Board wirklich gelöscht werden?</p>

                <div class="button-container">
                    <button type="reset" onclick="hideOverlayContainer('deleteBoardForm')" class="button-secondary">
                        Abbrechen
                    </button>
                    <a href="{{ route('deleteBoard', ['key' => $board->key]) }}">
                        <button class="button-small">
                            Löschen
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Skript für Drag and Drop -->
    <!-- Ins HTML eingebettet, damit es keine Probleme mit der Route und dem csrf-Token gibt -->
    <script>
        function drag(event, taskId) {
            console.log('---> DRAG');
            event.dataTransfer.setData("taskId", taskId);
        }

        function drop(event, $key, $status) {
            event.preventDefault();
            event.target.classList.remove('allow-drop');
            let $id = event.dataTransfer.getData("taskId");
            console.log('---> DROP: ' + $status);
            console.log('---> TASK: ' + $id);
            let url = '{{ route("updateStatus", [":key", ":id", ":status"]) }}';
            url = url.replace(':key', $key)
            url = url.replace(':id', $id);
            url = url.replace(':status', $status);
            location.href = url;
        }

        function allowDrop(event) {
            event.preventDefault();

        }
    </script>
</div>
</x-guest-layout>
