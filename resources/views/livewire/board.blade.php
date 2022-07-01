<div>
<script src="{{ asset('js/kanban.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/kanban.css') }}" >
    @include('header-links')


    <div class="board-main-content">
        <div class="board-page-area" id="board-area-left">
            <button onclick="showOverlayContainer('addTaskForm')">Neues Ticket</button>
            <div>
                Board-Key {{ $board->key }}
            </div>
            <a onclick="showOverlayContainer('updateBoardForm')">
               Board bearbeiten
            </a>
        </div>
        <div class="board-page-area" id="board-area-middle">
            {{ $board->title }}
            <div class="task-board-container border-slate-300">
                <div class="task-column">
                    {{-- Column 'Offen' --}}
                    <div class="bg-slate-50 border-b-2 border-slate-300">Offen</div>
                    @foreach($board->tasks()->where('status', 0)->get() as $task)


{{--                            @include('pm.taskDialog', ['task' => $task])--}}
                    {{ $task->title }}
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Column 'In Arbeit' --}}
                <div class="task-column">
                    <div class="bg-slate-50 border-b-2 border-slate-300">In Arbeit</div>
                    @foreach($board->tasks()->where('status', 1)->get() as $task)

{{--                            @include('pm.taskDialog', ['task' => $task])--}}

                            {{ $task->title }}
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Column 'Abgeschlossen' --}}
                <div class="task-column">
                    <div class="bg-slate-50 border-b-2 border-slate-300">Abgeschlossen</div>
                    @foreach($board->tasks()->where('status', 2)->get() as $task)

{{--                            @include('pm.taskDialog', ['task' => $task])--}}

                            {{ $task->title }}
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Column 'Vergangen' --}}
                <div class="task-column">
                    <div class="bg-slate-50 border-b-2 border-slate-300">Vergangen</div>
                    @foreach($board->tasks()->where('status', 3)->get() as $task)

{{--                            @include('pm.taskDialog', ['task' => $task])--}}

                            {{ $task->title }}
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>
            </div>
        </div>
        <div class="board-page-area" id="board-area-right">
            Board Beschreibung
            {{ $board->description }}
        </div>

    </div>

    <div id="addTaskForm" class="overlay-container"> {{-- Form to add new task, hidden unless "Neue Aufgabe"-button is clicked --}}
        <div class="overlay">
            <form method="POST" action="{{route('addTask', ['key' => $board->key])}}">
                @csrf
                <div class="overlay-section">
                    <p>Neuen Task erstellen</p>
                    <label for="title">Titel</label>
                    <input type="text" name="title" required>
                    <br>
                    <label for="description">Beschreibung</label>
                    <textarea name="description" rows="5" maxlength="2500"></textarea>
                    <br>
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
                        <button type="reset" onclick="hideOverlayContainer('addTaskForm')" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                            Abbrechen
                        </button>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Speichern
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="updateBoardForm" class="overlay-container"> {{-- Form to add new task, hidden unless "Neue Aufgabe"-button is clicked --}}
        <div class="overlay">
            <form method="POST" action="{{route('updateBoard', ['key' => $board->key])}}">
                @csrf
                <div class="overlay-section">
                    <p>Board bearbeiten</p>
                    <label for="title">Titel</label>
                    <input type="text" name="title" value="{{ $board->title }}" required>
                    <br>
                    <label for="description">Beschreibung</label>
                    <textarea name="description" rows="5" maxlength="2500" value="{{ $board->description }}"></textarea>
                    <br>

                    <div class="button-container">
                        <button type="reset" onclick="hideOverlayContainer('updateBoardForm')" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-200 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                            Abbrechen
                        </button>
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Speichern
                        </button>
                    </div>
                </div>
            </form>
            <!-- Board-Löschen-Button -->
        </div>
    </div>
</div>
