{{-- selbst erstellter Code --}}
<div id="updateBoardForm" class="overlay-container">
    <div class="overlay">
        <form method="POST" action="{{ route('updateBoard', ['key' => $board->key]) }}">
            @csrf
            <div class="overlay-section">

                <h3>Board bearbeiten</h3>

                <a onclick="showOverlayContainer('deleteBoardForm')" class="link-secondary">Board löschen</a>

                <label for="title">Titel</label>
                <input type="text" name="title" value="{{ $board->title }}" maxlength="255" required>

                <label for="description">Beschreibung</label>
                <textarea name="description" rows="5" maxlength="1000"
                          placeholder="Hier könnte eine tolle Beschreibung stehen">{{ $board->description }}</textarea>
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
