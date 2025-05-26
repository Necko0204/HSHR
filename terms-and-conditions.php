<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Platform - Terms and Conditions</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <style>
        :root {
            --primary-color: #ff1e56; /* Neon red */
            --secondary-color: #ffac41; /* Neon orange */
            --text-color: #ff8c42;
            --background-color: #121212; /* Dark futuristic background */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            background: var(--background-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        canvas {
        position: fixed;
        width: 100%;
        height: 100%;
        }

        a {
        position: absolute;
        bottom: 2vmin;
        right: 2vmin;
        color: rgba(255,255,255,0.2);
        text-decoration: none;
        }

        a:hover {
        color: #fff;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
    max-width: 800px;
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.header {
    text-align: center;
    margin-bottom: 2rem;
}

.logo {
    width: 150px;
    border-radius: 50%;
    object-fit: cover;
}

h1 {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
}

.subtitle {
    font-size: 1rem;
    color: var(--secondary-color);
}

section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-left: 4px solid var(--primary-color);
    background: #fff;
    border-radius: 6px;
}

h2 {
    font-size: 1.25rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

ul {
    padding-left: 1rem;
}

li {
    margin-bottom: 0.5rem;
    position: relative;
    padding-left: 1rem;
}

li::before {
    content: '•';
    color: var(--primary-color);
    font-weight: bold;
    position: absolute;
    left: 0;
}

.btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn:hover {
    background: #b71c1c;
}

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            section {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
  
    <div id="particles-js"></div>
    <canvas></canvas>
    <div class="container">
        <div class="header">
            <img src="images/landscape_logo.svg" alt="Logo" class="logo">
            <h1>Terms and Conditions</h1>
            <p class="subtitle">Holy Spirit School of Imus Inc. HR Platform</p>
        </div>

        <section>
            <h2>Use of the Platform</h2>
            <ul>
                <li>Authorized school staff, HR personnel, and admins only.</li>
                <li>Unauthorized access or sharing login credentials is prohibited.</li>
                <li>All data entered must be accurate and up-to-date.</li>
            </ul>
        </section>

        <section>
            <h2>Employee Confidentiality</h2>
            <ul>
                <li>Employee records and payroll must remain confidential.</li>
                <li>Only authorized HR personnel may access employee data.</li>
                <li>Breaches of confidentiality may result in legal action.</li>
            </ul>
        </section>

        <section>
            <h2>Data Privacy and Security</h2>
            <ul>
                <li>Strict security protocols protect all data.</li>
                <li>Users must log out after each session.</li>
                <li>Report security breaches to IT immediately.</li>
            </ul>
        </section>
    </div>

    <script>
        // particlesJS("particles-js", {
        //     "particles": {
        //         "number": {
        //             "value": 80
        //         },
        //         "size": {
        //             "value": 3
        //         },
        //         "move": {
        //             "speed": 2
        //         },
        //         "color": {
        //             "value": "#ff1e56"
        //         },
        //         "line_linked": {
        //             "enable": true,
        //             "distance": 150,
        //             "color": "#ffac41"
        //         }
        //     }
        // });

        /*          *     .        *  .    *    *   . 
 .  *  move your mouse to over the stars   .
 *  .  .   change these values:   .  *
   .      * .        .          * .       */
const STAR_COLOR = '#fff';
const STAR_SIZE = 3;
const STAR_MIN_SCALE = 0.2;
const OVERFLOW_THRESHOLD = 50;
const STAR_COUNT = ( window.innerWidth + window.innerHeight ) / 8;

const canvas = document.querySelector( 'canvas' ),
      context = canvas.getContext( '2d' );

let scale = 1, // device pixel ratio
    width,
    height;

let stars = [];

let pointerX,
    pointerY;

let velocity = { x: 0, y: 0, tx: 0, ty: 0, z: 0.0005 };

let touchInput = false;

generate();
resize();
step();

window.onresize = resize;
canvas.onmousemove = onMouseMove;
canvas.ontouchmove = onTouchMove;
canvas.ontouchend = onMouseLeave;
document.onmouseleave = onMouseLeave;

function generate() {

   for( let i = 0; i < STAR_COUNT; i++ ) {
    stars.push({
      x: 0,
      y: 0,
      z: STAR_MIN_SCALE + Math.random() * ( 1 - STAR_MIN_SCALE )
    });
   }

}

function placeStar( star ) {

  star.x = Math.random() * width;
  star.y = Math.random() * height;

}

function recycleStar( star ) {

  let direction = 'z';

  let vx = Math.abs( velocity.x ),
	    vy = Math.abs( velocity.y );

  if( vx > 1 || vy > 1 ) {
    let axis;

    if( vx > vy ) {
      axis = Math.random() < vx / ( vx + vy ) ? 'h' : 'v';
    }
    else {
      axis = Math.random() < vy / ( vx + vy ) ? 'v' : 'h';
    }

    if( axis === 'h' ) {
      direction = velocity.x > 0 ? 'l' : 'r';
    }
    else {
      direction = velocity.y > 0 ? 't' : 'b';
    }
  }
  
  star.z = STAR_MIN_SCALE + Math.random() * ( 1 - STAR_MIN_SCALE );

  if( direction === 'z' ) {
    star.z = 0.1;
    star.x = Math.random() * width;
    star.y = Math.random() * height;
  }
  else if( direction === 'l' ) {
    star.x = -OVERFLOW_THRESHOLD;
    star.y = height * Math.random();
  }
  else if( direction === 'r' ) {
    star.x = width + OVERFLOW_THRESHOLD;
    star.y = height * Math.random();
  }
  else if( direction === 't' ) {
    star.x = width * Math.random();
    star.y = -OVERFLOW_THRESHOLD;
  }
  else if( direction === 'b' ) {
    star.x = width * Math.random();
    star.y = height + OVERFLOW_THRESHOLD;
  }

}

function resize() {

  scale = window.devicePixelRatio || 1;

  width = window.innerWidth * scale;
  height = window.innerHeight * scale;

  canvas.width = width;
  canvas.height = height;

  stars.forEach( placeStar );

}

function step() {

  context.clearRect( 0, 0, width, height );

  update();
  render();

  requestAnimationFrame( step );

}

function update() {

  velocity.tx *= 0.96;
  velocity.ty *= 0.96;

  velocity.x += ( velocity.tx - velocity.x ) * 0.8;
  velocity.y += ( velocity.ty - velocity.y ) * 0.8;

  stars.forEach( ( star ) => {

    star.x += velocity.x * star.z;
    star.y += velocity.y * star.z;

    star.x += ( star.x - width/2 ) * velocity.z * star.z;
    star.y += ( star.y - height/2 ) * velocity.z * star.z;
    star.z += velocity.z;
  
    // recycle when out of bounds
    if( star.x < -OVERFLOW_THRESHOLD || star.x > width + OVERFLOW_THRESHOLD || star.y < -OVERFLOW_THRESHOLD || star.y > height + OVERFLOW_THRESHOLD ) {
      recycleStar( star );
    }

  } );

}

function render() {

  stars.forEach( ( star ) => {

    context.beginPath();
    context.lineCap = 'round';
    context.lineWidth = STAR_SIZE * star.z * scale;
    context.globalAlpha = 0.5 + 0.5*Math.random();
    context.strokeStyle = STAR_COLOR;

    context.beginPath();
    context.moveTo( star.x, star.y );

    var tailX = velocity.x * 2,
        tailY = velocity.y * 2;

    // stroke() wont work on an invisible line
    if( Math.abs( tailX ) < 0.1 ) tailX = 0.5;
    if( Math.abs( tailY ) < 0.1 ) tailY = 0.5;

    context.lineTo( star.x + tailX, star.y + tailY );

    context.stroke();

  } );

}

function movePointer( x, y ) {

  if( typeof pointerX === 'number' && typeof pointerY === 'number' ) {

    let ox = x - pointerX,
        oy = y - pointerY;

    velocity.tx = velocity.tx + ( ox / 8*scale ) * ( touchInput ? 1 : -1 );
    velocity.ty = velocity.ty + ( oy / 8*scale ) * ( touchInput ? 1 : -1 );

  }

  pointerX = x;
  pointerY = y;

}

function onMouseMove( event ) {

  touchInput = false;

  movePointer( event.clientX, event.clientY );

}

function onTouchMove( event ) {

  touchInput = true;

  movePointer( event.touches[0].clientX, event.touches[0].clientY, true );

  event.preventDefault();

}

function onMouseLeave() {

  pointerX = null;
  pointerY = null;

}

    </script>
</body>
</html>
