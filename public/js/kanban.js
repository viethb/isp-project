// selbst erstellter Code
const columns = document.getElementsByClassName('task-column');

Array.from(columns).forEach(c => {
    console.log(c);

    c.addEventListener('dragenter', function (e) {
        console.log('DRAGENTER');
        e.preventDefault();
        Array.from(columns).forEach(c => {
            c.classList.remove('allow-drop');
        });
        c.classList.add('allow-drop');
    });
});


function showOverlayContainer (taskId) {
    document.getElementById(taskId).style.display = "block";
}

function hideOverlayContainer (elementId) {
    document.getElementById(elementId).style.display = "none";
}
