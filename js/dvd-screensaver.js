document.addEventListener('DOMContentLoaded', () => {
    const dvdLogo = document.getElementById('dvd-logo');
    const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
    let x = Math.random() * (window.innerWidth - dvdLogo.offsetWidth);
    let y = Math.random() * (window.innerHeight - dvdLogo.offsetHeight);
    let xSpeed = 1; // Reduced from 2 to 1
    let ySpeed = 1; // Reduced from 2 to 1

    function updatePosition() {
        if (x + dvdLogo.offsetWidth >= window.innerWidth || x <= 0) {
            xSpeed = -xSpeed;
            changeColor();
        }
        if (y + dvdLogo.offsetHeight >= window.innerHeight || y <= 0) {
            ySpeed = -ySpeed;
            changeColor();
        }

        x += xSpeed;
        y += ySpeed;

        dvdLogo.style.left = `${x}px`;
        dvdLogo.style.top = `${y}px`;

        requestAnimationFrame(updatePosition);
    }

    function changeColor() {
        const color = colors[Math.floor(Math.random() * colors.length)];
        dvdLogo.style.backgroundColor = color;
    }

    updatePosition();
});

document.getElementById('toggle-dvd').addEventListener('click', () => {
    const dvdLogo = document.getElementById('dvd-logo');
    dvdLogo.style.display = dvdLogo.style.display === 'none' ? 'block' : 'none';
});
