 
function Confetti() {
  //canvas init
  var canvas = document.getElementById("confetti");
  var ctx = canvas.getContext("2d");

  //canvas dimensions
  var W = window.innerWidth;
  var H = window.innerHeight;
  canvas.width = W;
  canvas.height = H;
  
  //particles
  var mp = 150; //max particles
  var types = ['circle', 'circle', 'triangle', 'triangle', 'line'];
  var colors = [
    [238, 96, 169],
    [68, 213, 217],
    [245, 187, 152],
    [144, 148, 188],
    [235, 234, 77]
  ];
  var angles = [
    [4,0,4,4],
    [2,2,0,4],
    [0,4,2,2],
    [0,4,4,4]
  ]
  var particles = [];
  for (var i = 0; i < mp; i++) {
    particles.push({
      x: Math.random() * W, //x-coordinate
      y: Math.random() * H, //y-coordinate
      r: Math.random() * 4 + 1, //radius
      d: Math.random() * mp, //density
      l: Math.floor(Math.random()*65+-30), // line angle
      a: angles[Math.floor(Math.random()*angles.length)], // triangle rotation
      c: colors[Math.floor(Math.random()*colors.length)], // color
      t: types[Math.floor(Math.random()*types.length)] //shape 
    })
  }
  
  function draw(){
    ctx.clearRect(0, 0, W, H);
    

    for (var i = 0; i < mp; i++) {
      var p = particles[i];
      var op = (p.r <= 3) ? 0.4 : 0.8;
      
      if (p.t == 'circle'){
        ctx.fillStyle = "rgba(" + p.c + ", "+ op +")";
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2, true);
        ctx.fill();
      } else if (p.t == 'triangle'){
        ctx.fillStyle = "rgba(" + p.c + ", "+ op +")";
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        ctx.lineTo(p.x + (p.r*p.a[0]), p.y + (p.r*p.a[1]));
        ctx.lineTo(p.x + (p.r*p.a[2]), p.y + (p.r*p.a[3]));
        ctx.closePath();
        ctx.fill(); 
      } else if (p.t == 'line') {
        ctx.strokeStyle = "rgba(" + p.c + "," + op +")";
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        ctx.lineTo(p.x + p.l, p.y + (p.r * 5));
        ctx.lineWidth = 2;
        ctx.stroke();
      } 

    }
    confettitUpdate();
  }



  function confettitUpdate() {

    for (var i = 0; i < mp; i++) {
      var p = particles[i];
      p.y += Math.cos(p.d) + 1 + p.r / 2;
      p.x += Math.sin(0) * 2;
      
      if (p.x > W + 5 || p.x < -5 || p.y > H) {
        particles[i] = {
          x: Math.random() * W,
          y: -10,
          r: p.r,
          d: p.d,
          l: p.l,
          a: p.a,
          c: p.c,
          t: p.t
        };
      }
    }
  }

  //animation loop
  setInterval(draw, 23);

}

window.onload = function(){
  Confetti();
    
 
Player.src = root + "game_quizz_data/sons/musique.mp3"; 
Player.play();



        
//   window.resizeWindow = function() {
//     Confetti();
//   };

//   window.addEventListener('resize', resizeWindow, false);
};




//  document.write('<embed src="/game_quizz_data/sons/musique.mp3" width="0" height="0" controls="console" loop="false" autostart="true" ></embed>');
