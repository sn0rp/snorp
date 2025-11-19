<?php
require_once 'vendor/autoload.php';

ini_set('display_errors', 0);

function incrementVisitCount() {
    $countFile = __DIR__ . '/visit_count.txt';
    if (file_exists($countFile)) {
        $count = (int)file_get_contents($countFile);
        $count++;
    } else {
        $count = 1;
    }
    file_put_contents($countFile, $count, LOCK_EX);
    return $count;
}

$visitCount = incrementVisitCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:image" content="" />
    <meta name="twitter:image" content="" />
    <link rel="icon" href="/images/snorp-sprite.png" type="image/png">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/catscape.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital@0;1&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <script src="/js/mouse-follower.js" defer></script>
    <script src="/js/dvd-screensaver.js" defer></script>
    <script src="/js/badge-scroll.js" defer></script>
    <script src="/js/wobbly-text.js" defer></script>
    <script src="/js/virus-attack.js" defer></script>
    <script src="/js/click-explosion.js" defer></script>
</head>
<body class="biznus-page">
    <svg style="display: none;">
        <symbol id="icon-house" viewBox="0 0 576 512">
            <path d="M575.8 255.5c0 18-15 32.1-32 32.1l-32 0 .7 160.2c0 2.7-.2 5.4-.5 8.1l0 16.2c0 22.1-17.9 40-40 40l-16 0c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1L416 512l-24 0c-22.1 0-40-17.9-40-40l0-24 0-64c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 64 0 24c0 22.1-17.9 40-40 40l-24 0-31.9 0c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2l-16 0c-22.1 0-40-17.9-40-40l0-112c0-.9 0-1.9 .1-2.8l0-69.7-32 0c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z"/>
        </symbol>
        <symbol id="icon-github" viewBox="0 0 496 512">
            <path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3 .3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5 .3-6.2 2.3zm44.2-1.7c-2.9 .7-4.9 2.6-4.6 4.9 .3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3 .7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3 .3 2.9 2.3 3.9 1.6 1 3.6 .7 4.3-.7 .7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3 .7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3 .7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/>
        </symbol>
        <symbol id="icon-x-twitter" viewBox="0 0 512 512">
            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
        </symbol>
        <symbol id="icon-link" viewBox="0 0 640 512">
            <path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/>
        </symbol>
    </svg>
    <div class="page-wrapper">
        <div class="container">
            <header>
                <h1 id="wobble">Snorp</h1>
                <nav>
                    <a href="/" class="home-link">
                        <svg class="icon"><use xlink:href="#icon-house"></use></svg>
                    </a>
                    <a href="https://github.com/sn0rp" class="external-link">
                        <svg class="icon"><use xlink:href="#icon-github"></use></svg>
                    </a>
                    <a href="https://x.com/_snorp" class="external-link">
                        <svg class="icon"><use xlink:href="#icon-x-twitter"></use></svg>
                    </a>
                </nav>
                <img src="/images/under-construction.gif" alt="under construction" style="width: 210px;"/>
                <div class="toggle-buttons">
                    <button id="toggle-dvd" class="toggle-button">Toggle DVD</button>
                    <button id="toggle-follower" class="toggle-button">Toggle Follower</button>
                    <button id="virus-attack" class="toggle-button">DON'T CLICK</button>
                </div>
            </header>
            
            <main>
                <div class="biznus-tabs">
                    <button class="biznus-tab active" data-tab="novels">Novels</button>
                    <button class="biznus-tab" data-tab="calculations">Calculations</button>
                    <button class="biznus-tab" data-tab="masterpieces">Masterpieces</button>
                    <button class="biznus-tab" data-tab="tedtalks">TED Talks</button>
                </div>

                <div id="novels" class="biznus-content active">
                    <div class="instructions">
                        <h3>Novels</h3>
                        <p>A minimalist text editor for your literary masterpieces.</p>
                        <ul>
                            <li>Just start typing in the box</li>
                            <li>Select text to format</li>
                            <li>Ctrl+B: Bold</li>
                            <li>Ctrl+I: Italic</li>
                            <li>Ctrl+U: Underline</li>
                        </ul>
                    </div>
                    <iframe class="biznus-frame" 
                            sandbox="allow-same-origin allow-scripts allow-forms"
                            srcdoc="<!DOCTYPE html><html><head><style>html,body{margin:0;padding:20px;height:100%;background:#2a0a4a;color:white;font-family:sans-serif}</style></head><body contenteditable>Start typing here...</body></html>">
                    </iframe>
                </div>

                <div id="calculations" class="biznus-content">
                    <div class="instructions">
                        <h3>Calculations</h3>
                        <p>A lightweight spreadsheet for your number crunching needs.</p>
                        <ul>
                            <li>Click any cell to edit</li>
                            <li>Use = to start a formula (e.g., =A1+B1)</li>
                            <li>Supports basic arithmetic operations (+, -, *, /)</li>
                            <li>References cells using column letter + row number (A1, B2, etc.)</li>
                        </ul>
                    </div>
                    <iframe class="biznus-frame" 
                            sandbox="allow-same-origin allow-scripts allow-forms"
                            srcdoc="<!DOCTYPE html><html><head><style>body{background:#2a0a4a;color:white;padding:10px}table{border-collapse:collapse}input{background:#2a0a4a;color:white;border:none;width:4rem;text-align:right}td{border:1px solid #8a2be2;color:white;text-align:right;padding:2px 4px}</style></head><body><table id=t><script>for(I=[],D={},C={},calc=()=>I.forEach(e=>{try{e.value=D[e.id]}catch(e){}}),t.style.borderCollapse='collapse',i=0;i<101;i++)for(r=t.insertRow(-1),j=0;j<27;j++)c=String.fromCharCode(65+j-1),d=r.insertCell(-1),d.innerHTML=i?j?'':i:c,i*j&&I.push(d.appendChild((f=>(f.id=c+i,f.onfocus=e=>f.value=C[f.id]||'',f.onblur=e=>{C[f.id]=f.value,calc()},get=()=>{let v=C[f.id]||'';if('='!=v.charAt(0))return isNaN(parseFloat(v))?v:parseFloat(v);with(D)return eval(v.substring(1))},Object.defineProperty(D,f.id,{get:get}),Object.defineProperty(D,f.id.toLowerCase(),{get:get}),f))(document.createElement('input'))))</script></table></body></html>">
                    </iframe>
                </div>

                <div id="masterpieces" class="biznus-content">
                    <div class="instructions">
                        <h3>Masterpieces</h3>
                        <p>Express your artistic vision with this simple drawing tool.</p>
                        <ul>
                            <li>Click/touch and drag to draw</li>
                            <li>Right click or long press to save</li>
                            <li>Press C or double tap to clear</li>
                        </ul>
                    </div>
                    <iframe class="biznus-frame" 
                            sandbox="allow-same-origin allow-scripts allow-forms"
                            srcdoc="<!DOCTYPE html><html><head><style>
                            body {
                                margin: 0;
                                padding: 0;
                                background: #2a0a4a;
                                overflow: hidden;
                                width: 100%;
                                height: 100vh;
                            }
                            canvas {
                                display: block;
                                touch-action: none;
                            }
                            </style></head><body><canvas id='v'></canvas><script>
                            const canvas = document.getElementById('v');
                            const ctx = canvas.getContext('2d');
                            
                            function resizeCanvas() {
                                canvas.width = window.innerWidth;
                                canvas.height = window.innerHeight;
                                ctx.strokeStyle = 'white';
                                ctx.lineWidth = 2;
                            }
                            
                            resizeCanvas();
                            window.addEventListener('resize', resizeCanvas);
                            
                            let isDrawing = false;
                            let lastX, lastY;
                            let lastTap = 0;
                            
                            function getCoords(e) {
                                if (e.touches && e.touches[0]) {
                                    return {
                                        x: e.touches[0].pageX,
                                        y: e.touches[0].pageY
                                    };
                                }
                                return {
                                    x: e.pageX,
                                    y: e.pageY
                                };
                            }
                            
                            function startDraw(e) {
                                isDrawing = true;
                                const coords = getCoords(e);
                                lastX = coords.x;
                                lastY = coords.y;
                                ctx.beginPath();
                                ctx.moveTo(lastX, lastY);
                            }
                            
                            function draw(e) {
                                if (!isDrawing) return;
                                e.preventDefault();
                                const coords = getCoords(e);
                                ctx.lineTo(coords.x, coords.y);
                                ctx.stroke();
                                [lastX, lastY] = [coords.x, coords.y];
                            }
                            
                            function stopDraw(e) {
                                if (!isDrawing) return;
                                isDrawing = false;
                                ctx.stroke();
                                
                                if (e.type === 'touchend') {
                                    const now = Date.now();
                                    if (now - lastTap < 300) {
                                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                                    }
                                    lastTap = now;
                                }
                            }
                            
                            // Mouse Events
                            canvas.addEventListener('mousedown', startDraw);
                            canvas.addEventListener('mousemove', draw);
                            canvas.addEventListener('mouseup', stopDraw);
                            canvas.addEventListener('mouseout', stopDraw);
                            
                            // Touch Events
                            canvas.addEventListener('touchstart', startDraw, { passive: false });
                            canvas.addEventListener('touchmove', draw, { passive: false });
                            canvas.addEventListener('touchend', stopDraw);
                            
                            // Save functionality
                            let touchTimer;
                            canvas.addEventListener('touchstart', () => {
                                touchTimer = setTimeout(() => {
                                    const link = document.createElement('a');
                                    link.download = 'masterpiece.png';
                                    link.href = canvas.toDataURL();
                                    link.click();
                                }, 800);
                            });
                            
                            canvas.addEventListener('touchend', () => {
                                clearTimeout(touchTimer);
                            });
                            
                            document.addEventListener('keydown', (e) => {
                                if (e.key.toLowerCase() === 's') {
                                    const link = document.createElement('a');
                                    link.download = 'masterpiece.png';
                                    link.href = canvas.toDataURL();
                                    link.click();
                                } else if (e.key.toLowerCase() === 'c') {
                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                }
                            });
                            </script></body></html>">
                    </iframe>
                </div>

                <div id="tedtalks" class="biznus-content">
                    <div class="instructions">
                        <h3>TED Talks</h3>
                        <p>Create engaging presentations that will revolutionize the world.</p>
                        <ul>
                            <li>Each box is a slide</li>
                            <li>Ctrl+Alt+1: Make text into heading</li>
                            <li>Ctrl+Alt+2: Make text into normal</li>
                            <li>Ctrl+Alt+3: Align left</li>
                            <li>Ctrl+Alt+4: Align center</li>
                            <li>Ctrl+Alt+5: Align right</li>
                            <li>Ctrl+Alt+6: Decrease indent</li>
                            <li>Ctrl+Alt+7: Increase indent</li>
                            <li>Ctrl+Alt+8: Bullet points</li>
                        </ul>
                    </div>
                    <iframe class="biznus-frame" 
                            sandbox="allow-same-origin allow-scripts allow-forms"
                            srcdoc="<!DOCTYPE html><html><head><style>
                            @page { size: 6in 8in landscape; }
                            body {
                                background: #2a0a4a;
                                color: white;
                                margin: 0;
                                padding: 10px;
                                font-family: sans-serif;
                            }
                            .slide {
                                position: relative;
                                width: 90%;
                                padding-top: 50%;
                                margin: 5% auto;
                                border: 1px solid #8a2be2;
                                page-break-after: always;
                            }
                            .slide-content {
                                position: absolute;
                                right: 5%;
                                bottom: 5%;
                                left: 5%;
                                top: 5%;
                                font-size: 5vmin;
                                outline: none;
                            }
                            </style></head><body><script>
                            d=document;
                            for(i=0;i<50;i++) {
                                d.body.innerHTML += `
                                    <div class='slide'>
                                        <div class='slide-content' contenteditable>Click to edit slide ${i+1}</div>
                                    </div>
                                `;
                            }
                            d.querySelectorAll('.slide-content').forEach(e=>e.onkeydown=e=>{
                                n=e.ctrlKey&&e.altKey&&e.keyCode-49,
                                x=['formatBlock','formatBlock','justifyLeft','justifyCenter','justifyRight','outdent','indent','insertUnorderedList'][n],
                                y=['<h1>','<div>'][n],
                                x&&document.execCommand(x,!1,y)
                            })
                            </script></body></html>">
                    </iframe>
                </div>
            </main>
            
            <footer>
                <div id="hit-counter">
                    <span>Visits</span>
                    <div class="counter">
                        <?php 
                            $paddedCount = str_pad($visitCount, 8, '0', STR_PAD_LEFT);
                            for ($i = 0; $i < strlen($paddedCount); $i++) {
                                echo '<span class="digit">' . $paddedCount[$i] . '</span>';
                            }
                        ?>
                    </div>
                </div>
                <p>&copy; <?php echo date("Y"); ?> Snorp. All rights reserved.</p>
                <img src="/images/datboi.gif" alt="datboi" id="datboi">
            </footer>
        </div>
        
        <div id="catscape-container" class="hidden">
            <div id="catscape-titlebar">
                <span>Snorpscape Sailor 1.0</span>
                <button id="catscape-open-main">Open in Main Window</button>
                <button id="catscape-close">&times;</button>
            </div>
            <div id="catscape-addressbar">
                <input type="text" id="catscape-url" readonly>
            </div>
            <iframe id="catscape-frame"></iframe>
            <div id="catscape-statusbar">
                <span id="catscape-status"></span>
                <span id="catscape-meta-title"></span>
            </div>
        </div>
    </div>
    <div id="mouse-follower"></div>
    <div id="dvd-logo">DVD</div>
    <img src="/images/howard.gif" alt="howard" id="howard">
    <img src="/images/jupiter.gif" alt="jupiter" id="jupiter">
    <img src="/images/sasha.gif" alt="sasha" id="sasha">
    <script>
    document.querySelectorAll('.biznus-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and contents
            document.querySelectorAll('.biznus-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.biznus-content').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });
    </script>
</body>
</html> 