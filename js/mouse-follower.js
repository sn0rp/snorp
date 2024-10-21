document.addEventListener('DOMContentLoaded', () => {
    const follower = document.getElementById('mouse-follower');
    let mouseX = 0, mouseY = 0;
    let followerX = window.innerWidth / 2, followerY = window.innerHeight / 2;
    let isFirstMove = true;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        if (isFirstMove) {
            followerX = mouseX - 200; // Spawn 200px to the left of the initial mouse position
            followerY = mouseY + 200; // Spawn 200px below the initial mouse position
            isFirstMove = false;
            setTimeout(() => animate(), 100);
        }
    });

    function animate() {
        let distX = mouseX - followerX;
        let distY = mouseY - followerY;
        
        followerX += distX * 0.005;
        followerY += distY * 0.005;

        follower.style.left = followerX + 'px';
        follower.style.top = followerY + 'px';

        requestAnimationFrame(animate);
    }
});

document.getElementById('toggle-follower').addEventListener('click', () => {
    const follower = document.getElementById('mouse-follower');
    follower.style.display = follower.style.display === 'none' ? 'block' : 'none';
});
