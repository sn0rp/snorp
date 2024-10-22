let sparks=75; // how many sparks per clicksplosion
let explodeSpeed=15; // how fast - smaller is faster
let bangs=5; // how many can be launched simultaneously (note that using too many can slow the script down)
const colors = ['#03f', '#f03', '#0e0', '#93f', '#0cf', '#f93', '#f0c'];

let intensity = [];
let Xpos = [];
let Ypos = [];
let dX = [];
let dY = [];
let stars = [];
let decay = [];
let timers = [];
let swide = 800;
let shigh = 600;
let sleft = 0, sdown = 0;
let count = 0;

function addLoadEvent(funky) {
  var oldonload=window.onload;
  if (typeof(oldonload)!='function') window.onload=funky;
  else window.onload=function() {
    if (oldonload) oldonload();
    funky();
  }
}

addLoadEvent(clicksplode);

function clicksplode() {
  if (document.getElementById) {
    window.addEventListener('scroll', set_scroll);
    window.addEventListener('resize', set_width);
    document.addEventListener('click', eksplode);
    set_width();
    set_scroll();
    
    const fragment = document.createDocumentFragment();
    for (let i = 0; i < bangs * sparks; i++) {
      stars[i] = createDiv('*', 13);
      fragment.appendChild(stars[i]);
    }
    document.body.appendChild(fragment);
  }
}

function createDiv(char, size) {
  var div, sty;
  div=document.createElement('div');
  sty=div.style;
  sty.font=size+'px monospace';
  sty.position='absolute';
  sty.backgroundColor='transparent';
  sty.visibility='hidden';
  sty.zIndex='101';
  div.appendChild(document.createTextNode(char));
  return (div);
}

function bang(N) {
  let A = 0;
  const start = sparks * N;
  const end = sparks * (N + 1);
  
  for (let i = start; i < end; i++) { 
    if (decay[i]) {
      const Z = stars[i].style;
      Xpos[i] += dX[i];
      Ypos[i] += (dY[i] += 1.25 / intensity[N]);
      
      if (Xpos[i] >= swide || Xpos[i] < 0 || Ypos[i] >= shigh + sdown || Ypos[i] < 0) {
        decay[i] = 1;
      } else {
        Z.left = Xpos[i] + 'px';
        Z.top = Ypos[i] + 'px';
      }
      
      if (decay[i] === 15) Z.fontSize = '7px';
      else if (decay[i] === 7) Z.fontSize = '2px';
      else if (decay[i] === 1) Z.visibility = 'hidden';
      
      decay[i]--;
    } else {
      A++;
    }
  }
  
  if (A !== sparks) timers[N] = setTimeout(() => bang(N), explodeSpeed);
}

function eksplode(e) { 
  set_scroll();
  const y = e.pageY || (event.y + sdown);
  const x = e.pageX || (event.x + sleft);
  const N = ++count % bangs;
  const M = Math.floor(Math.random() * 3 * colours.length);
  intensity[N] = 5 + Math.random() * 4;
  
  for (let i = N * sparks; i < (N + 1) * sparks; i++) {
    Xpos[i] = x;
    Ypos[i] = y - 5;
    dY[i] = (Math.random() - 0.5) * intensity[N];
    dX[i] = (Math.random() - 0.5) * (intensity[N] - Math.abs(dY[i])) * 1.25;
    decay[i] = 16 + Math.floor(Math.random() * 16);
    const Z = stars[i].style;
    Z.color = colours[M < colours.length ? (i % 2 ? count % colours.length : M) : (M < 2 * colours.length ? count % colours.length : i % colours.length)];
    Z.fontSize = '13px';
    Z.visibility = 'visible';
  }
  
  clearTimeout(timers[N]);
  bang(N);
} 

function set_width() {
  var sw_min=999999;
  var sh_min=999999;
  if (document.documentElement && document.documentElement.clientWidth) {
    if (document.documentElement.clientWidth>0) sw_min=document.documentElement.clientWidth;
    if (document.documentElement.clientHeight>0) sh_min=document.documentElement.clientHeight;
  }
  if (typeof(self.innerWidth)=='number' && self.innerWidth) {
    if (self.innerWidth>0 && self.innerWidth<sw_min) sw_min=self.innerWidth;
    if (self.innerHeight>0 && self.innerHeight<sh_min) sh_min=self.innerHeight;
  }
  if (document.body.clientWidth) {
    if (document.body.clientWidth>0 && document.body.clientWidth<sw_min) sw_min=document.body.clientWidth;
    if (document.body.clientHeight>0 && document.body.clientHeight<sh_min) sh_min=document.body.clientHeight;
  }
  if (sw_min==999999 || sh_min==999999) {
    sw_min=800;
    sh_min=600;
  }
  swide=sw_min-7;
  shigh=sh_min-7;
}

function set_scroll() {
  if (typeof(self.pageYOffset)=='number') {
    sdown=self.pageYOffset;
    sleft=self.pageXOffset;
  }
  else if (document.body && (document.body.scrollTop || document.body.scrollLeft)) {
    sdown=document.body.scrollTop;
    sleft=document.body.scrollLeft;
  }
  else if (document.documentElement && (document.documentElement.scrollTop || document.documentElement.scrollLeft)) {
    sleft=document.documentElement.scrollLeft;
    sdown=document.documentElement.scrollTop;
  }
  else {
    sdown=0;
    sleft=0;
  }
}
