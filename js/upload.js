var fileInputCount = 1;
var inputContainer = document.getElementById('files');
var currentInput = document.getElementById('file1');
var fileInputCountInput = document.getElementById('file-count');

currentInput.addEventListener('change', onFileSelected);

function onFileSelected(event) {
    if (event.target.value) {
        addNewFileInput();
    }
}

function addNewFileInput() {
    fileInputCount++;

    var group = document.createElement('div');
    group.classList.add('form__group');

    var label = document.createElement('label');
    label.setAttribute('for', 'file' + fileInputCount);
    label.innerHTML = 'Załącznik ' + fileInputCount + ':';

    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('id', 'file' + fileInputCount);
    input.setAttribute('name', 'file' + fileInputCount);
    input.classList.add('file');

    fileInputCountInput.setAttribute('value', fileInputCount);

    group.appendChild(label);
    group.appendChild(input);
    inputContainer.appendChild(group);

    currentInput.removeEventListener('change', onFileSelected);

    currentInput = input;
    currentInput.addEventListener('change', onFileSelected);
}