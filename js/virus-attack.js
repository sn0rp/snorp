var delay = 0; // seconds until attack starts
var seconds = 30; // seconds until attack complete (from start)

var colours = new Array("#090", "#0f0", "#6f6", "#cfc", "transparent");
var elems = new Array();
var chars = new Array();
var elmax = chmax = 0;

function initVirusAttack() {
    if (document.getElementById) {
        var i, j, txt, parent, span, words;
        getAllText(document.body);
        for (i = 0; i < elmax; i++) {
            txt = elems[i].nodeValue;
            parent = elems[i].parentNode;
            words = elems[i].nodeValue.split(" ");
            for (j = 0; j < words.length; j++) {
                span = document.createElement("span");
                span.appendChild(document.createTextNode(words[j]));
                parent.insertBefore(span, elems[i]);
                if (j != words.length - 1) parent.insertBefore(document.createTextNode(" "), elems[i]);
                chars[chmax++] = span;
            }
            parent.removeChild(elems[i]);
        }
        virus();
    }
}

function virus() {
    var i, t, j;
    for (i = 0; i < chmax; i++) {
        t = 1000 * (delay + seconds * Math.random());
        for (j = 0; j < colours.length; j++) setTimeout('chars[' + i + '].style.backgroundColor="' + colours[j] + '"', t + 100 * j);
        setTimeout('chars[' + i + '].style.visibility="hidden"', t + 100 * j);
    }
    setTimeout('document.body.style.cursor="pointer";document.body.onclick=function(){location.reload()};end()', 1000 * (delay + seconds));
}

function end() {
    document.body.style.backgroundColor = colours[elmax = ++elmax % colours.length];
    setTimeout("end()", 100);
}

function getAllText(el) {
    if (el.nodeType == 3 && !el.nodeValue.match(/^\s+$/)) elems[elmax++] = el;
    if (!el.childNodes.length) return;
    else for (var i = 0; i < el.childNodes.length; i++) getAllText(el.childNodes[i]);
}

document.addEventListener('DOMContentLoaded', function() {
    const virusButton = document.getElementById('virus-attack');
    if (virusButton) {
        virusButton.addEventListener('click', initVirusAttack);
    }
});
