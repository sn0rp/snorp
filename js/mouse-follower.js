document.addEventListener('DOMContentLoaded', () => {
    const follower = document.getElementById('mouse-follower');
    let mouseX = 0, mouseY = 0;
    let followerX = -100, followerY = -100; // Start off-screen
    let isVisible = false;

    // Initially hide the follower
    follower.style.display = 'none';

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        if (!isVisible) {
            // Show the follower on first mouse move
            follower.style.display = 'block';
            isVisible = true;
            // Position it off-screen based on mouse position
            followerX = mouseX < window.innerWidth / 2 ? -100 : window.innerWidth + 100;
            followerY = mouseY < window.innerHeight / 2 ? -100 : window.innerHeight + 100;
            requestAnimationFrame(animate);
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
