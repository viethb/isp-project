{{-- selbst erstellter Code --}}
<div id="addBoardForm" class="overlay-container">
    <div class="overlay">
        <form method="POST" action="{{route('addBoard')}}">
            @csrf
            <div class="overlay-section">

                <h3>Neues Board</h3>
                <label for="title">Titel</label>
                <input type="text" name="title" maxlength="255" required>

                <label for="description">Beschreibung</label>
                <textarea name="description" rows="5" maxlength="1000"></textarea>

                <div class="button-container">
                    <button type="reset" onclick="hideOverlayContainer('addBoardForm')" class="button-secondary">
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
