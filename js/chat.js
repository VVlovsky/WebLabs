var checkbox = document.querySelector('#chat__activate');
var chatForm = document.querySelector('.chat__form');


checkbox.addEventListener('change', onCheckboxChange);
chatForm.addEventListener('submit', onMessageSubmit)

function onCheckboxChange(event) {
    setChatActive(event.target.checked);
}

var sendButton = document.querySelector('.chat__send');
var pollXhr = null;
var usernameInput = document.querySelector('#username');
var messageInput = document.querySelector('#message');

function setChatActive(enabled) {
    usernameInput.disabled = !enabled;
    messageInput.disabled = !enabled;
    sendButton.disabled = !enabled;

    if (enabled) {
        fCurrMess();
        pollMessages();
    } else {
        setChatText('');
        pollXhr.abort();
    }
}



function fCurrMess() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'chat_poll.php?fetch=true');
    xhr.send();

    xhr.onload = function () {
        if (xhr.status != 200) {
            alert('Error ' + xhr.status + ': ' + xhr.statusText);
        } else {
            setChatText(xhr.responseText);
        }
    };

    xhr.onerror = function () {
        alert('Error');
    };
}


var chatRoom = document.querySelector('.chat__room');

function setChatText(messages) {
    chatRoom.value = messages;
}


function onMessageSubmit(event) {
    event.preventDefault();

    if (!usernameInput.value || !messageInput.value) {
        alert('Error! Username or message is empty!');
        return;
    }

    var formData = new FormData(event.target);
    var xhr = new XMLHttpRequest();

    xhr.open('POST', './chat_post.php');
    xhr.send(formData);

    chatRoom.value += usernameInput.value + ': ' + messageInput.value + '\n';

    messageInput.value = '';
}

function pollMessages() {
    pollXhr = new XMLHttpRequest();
    pollXhr.open('GET', './chat_poll.php');
    pollXhr.send();

    pollXhr.onload = function () {
        if (pollXhr.status != 200) {
        } else {
            setChatText(pollXhr.responseText);
            pollMessages();
        }
    };

    pollXhr.onerror = function () {
        alert('Error');
        pollMessages();
    };
}