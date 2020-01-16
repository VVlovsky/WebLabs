var styles = document.querySelectorAll('link[type="text/css"][media="screen"]');
var stylesList = document.querySelector('.js-styles-list');

for (var i = 0; i < styles.length; i++) {
    var style = styles[i];
    var element = document.createElement('li');

    element.innerHTML = style.getAttribute('title');
    element.setAttribute('onclick', 'setStyleActive(' + i + ')');
    stylesList.appendChild(element);
}

function setStyleActive (index) {
    console.log('set active: ', index, styles);

    for (var i = 0; i < styles.length; i++) {
        styles[i].disabled = true;
    }

    styles[index].disabled = false;
    document.cookie = 'style=' + index + ';';
}

window.addEventListener('load', function () {
    if (document.cookie) {
        var cookies = document.cookie.split(/; */);

        for (var i = 0; i < cookies.length; i++) {
            var data = cookies[i].split('=');

            if (data[0] == 'style') {
                setStyleActive(parseInt(data[1]));
            }
        }
    }
});