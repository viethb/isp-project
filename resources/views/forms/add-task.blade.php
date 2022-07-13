{{-- selbst erstellter Code --}}
<div id="addTaskForm" class="overlay-container">
    <div class="overlay">
        <form method="POST" action="{{route('addTask', ['key' => $board->key])}}">
            @csrf
            <div class="overlay-section">
                <h3>Neuen Task erstellen</h3>
                <label for="title">Titel</label>
                <input type="text" name="title" maxlength="255" required>

                <label for="description">Beschreibung</label>
                <textarea name="description" rows="5" maxlength="1000"></textarea>

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
                        <input type="text" name="assignee" maxlength="50">
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
