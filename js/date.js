function setDateInputToCurrentTime () {
    var now = new Date();
    document.getElementById('date').value =
        now.getFullYear() + '-' +
        (now.getMonth() + 1).toString().padStart(2, '0') + '-' +
        now.getDate().toString().padStart(2, '0');
}

function setTimeInputToCurrentTime () {
    var now = new Date();
    document.getElementById('time').value =
        now.getHours().toString().padStart(2, '0') + ':' +
        now.getMinutes().toString().padStart(2, '0');
}


function onDateInputChange (event) {
    var newValue = event.target.value;

    if (!/^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/g.test(newValue)) {
        alert('Invalid date');
        setDateInputToCurrentTime();
    }

    var now = new Date();
    var dateParts = newValue.split('-');
    var parsed = new Date(dateParts[0], parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));

    if (parsed > now) {
        alert('Future date');
        setDateInputToCurrentTime();
    }
}

function onTimeInputChange (event) {
    var newValue = event.target.value;

    if (!/^([01][0-9]|2[0-3]):[0-5][0-9]$/g.test(newValue)) {
        alert('Invalid time');
        setTimeInputToCurrentTime();
    }

    var now = new Date();
    var timeParts = newValue.split(':');
    var parsed = new Date(now.getFullYear(), now.getMonth(), now.getDate(), parseInt(timeParts[0]), parseInt(timeParts[1]));

    if (parsed > now) {
        alert('Future time');
        setTimeInputToCurrentTime();
    }
}

setDateInputToCurrentTime();
setTimeInputToCurrentTime();

document.getElementById('date').addEventListener('change', onDateInputChange);
document.getElementById('time').addEventListener('change', onTimeInputChange);