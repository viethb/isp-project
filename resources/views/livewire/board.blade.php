{{-- selbst erstellter Code --}}
<x-guest-layout>
<div>
    @include('header-links')

    {{-- Linker Bereich der Board-Ansicht --}}
    <div class="board-main-content">
        <div class="board-page-area" id="board-area-left">
            @auth
                <a href="{{ url('/welcome') }}" class="link-secondary">Zurück zu meinen Boards</a>
                @if($board->isUserOwner(Auth::id()))
                    <button class="button-small" onclick="showOverlayContainer('addTaskForm')">Neuer Task</button>
                @endif
            @endauth
            @guest
                <a href="{{ url('/') }}" class="link-secondary">Zu einem anderen Board</a>
            @endguest
            <div>
                <h4>Board-Key</h4>
                {{ $board->key }}
            </div>
            @auth
                @if($board->isUserOwner(Auth::id()))
                    <a onclick="showOverlayContainer('updateBoardForm')">
                        <img class="icon" src="{{ asset('images/pencil-icon.png') }}">
                       Board bearbeiten
                    </a>
                @endif
            @endauth
        </div>

        {{-- Mittlerer Hauptbereich der Board-Ansicht --}}
        <div class="board-page-area" id="board-area-middle">
            <h2>{{ $board->title }}</h2>
            <div class="task-board-container">
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 0)' ondragover="allowDrop(event)">
                    {{-- Spalte 'Offen' --}}
                    <div id="first-task-column-header" class="task-column-header">Offen</div>
                    @foreach($board->tasks()->where('status', 0)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Spalte 'In Arbeit' --}}
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 1)' ondragover="allowDrop(event)">
                    <div class="task-column-header">In Arbeit</div>
                    @foreach($board->tasks()->where('status', 1)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Spalte 'Abgeschlossen' --}}
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 2)' ondragover="allowDrop(event)">
                    <div class="task-column-header">Abgeschlossen</div>
                    @foreach($board->tasks()->where('status', 2)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>

                {{-- Spalte 'Vergangen' --}}
                <div class="task-column" ondrop='drop(event, "{{ $board->key }}", 3)' ondragover="allowDrop(event)">
                    <div id="last-task-column-header" class="task-column-header">Vergangen</div>
                    @foreach($board->tasks()->where('status', 3)->get() as $task)
                        @include('task', ['task' => $task, 'key' => $board->key])
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Rechter Bereich der Board-Ansicht --}}
        <div class="board-page-area" id="board-area-right">
            <div>
                <h4>Board-Ersteller</h4>
                <span>{{ $board->creator->name }}</span>
            </div>
            <div>
                <h4>Board Beschreibung</h4>
                <p>{{ $board->description }}</p>
            </div>
        </div>
    </div>

    {{-- Formular zum Erstellen eines neuen Tasks, nur sichtbar, wenn "Neuer Task" geklickt wird --}}
    @include('forms.add-task', ['board' => $board])

    {{-- Formular zum Bearbeiten der Board-Informationen und Löschen des Boards, nur sichtbar, wenn "Board bearbeiten" geklickt wird --}}
    @include('forms.update-board', ['board' => $board])

    {{-- Skript für Drag and Drop --}}
    {{-- Ins HTML eingebettet, damit es keine Probleme mit der Route und dem csrf-Token gibt --}}
    <script>
        function drag(event, taskId) {
            event.dataTransfer.setData("taskId", taskId);
        }

        function drop(event, $key, $status) {
            event.preventDefault();
            event.target.classList.remove('allow-drop');
            let $id = event.dataTransfer.getData("taskId");
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
