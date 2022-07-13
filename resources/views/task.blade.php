{{-- selbst erstellter Code --}}
<div class="task-small {{ isset($task->due_date) && $task->isDueIn() < 5 ? 'is-due' : ''  }}"
     onclick="showOverlayContainer({{ $task->id }})" draggable="true" ondragstart="drag(event, {{ $task->id }})">
    <div class="task-small-id">
        #{{ $task->task_number }}
    </div>
    <div class="task-small-title">
        {{ $task->title }}
    </div>
    <div class="task-small-priority">
        {!! $task->getPrioritySymbol() !!}
    </div>
    @if(isset($task->assignee))
        <img src="https://eu.ui-avatars.com/api/?name={{$task->assignee}}&background=E8EDED&color=26C7B9&font-size=0.65&bold=true&rounded=true&uppercase=false"
             title="{{ $task->assignee }}"/>
    @endif
    <div class="task-small-due-date">
        {{ $task->due_date }}
    </div>
</div>

{{-- Formular zum Anschauen und Anpassen eines Tasks, nur sichtbar, wenn ein task-small-Item angeklickt wird --}}
@include('forms.update-task', ['task' => $task, 'key' => $key])
