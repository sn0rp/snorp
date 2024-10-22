const speed = 5; // speed of wobbling (lower is faster)
const height = 5; // height of wobbling in pixels

function initWobble() {
    if (document.getElementById) {
        const wobble = document.getElementById("wobble");
        const wobtxt = wobble.innerText || wobble.textContent;
        wobble.innerHTML = '';
        for (let i = 0; i < wobtxt.length; i++) {
            const wobli = document.createElement("span");
            wobli.setAttribute("id", "wobb" + i);
            wobli.style.position = "relative";
            wobli.appendChild(document.createTextNode(wobtxt.charAt(i)));
            wobble.appendChild(wobli);
        }
        animateWobble();
    }
}

function animateWobble() {
    const wobble = document.getElementById("wobble");
    const wobtxt = wobble.innerText || wobble.textContent;
    const time = Date.now() / (50 * speed);
    for (let i = 0; i < wobtxt.length; i++) {
        const wobli = document.getElementById("wobb" + i);
        wobli.style.top = Math.round(height * Math.sin(i * 0.3 + time)) + "px";
    }
    requestAnimationFrame(animateWobble);
}

document.addEventListener('DOMContentLoaded', initWobble);
