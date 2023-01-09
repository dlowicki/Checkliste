<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="style.css" /> 
		<title>Checkliste</title>
	</head>
	<body>
		<?php
		if(isset($_GET['wplID']))
		{
			return;
		}
		?>
		<!--<div class="box-input-wpl">
			<h2>Identifikation eintragen</h2>
			<input type='text' id='wplID' placeholder='Identifikation...'>
			<button>Absenden</button>
		</div>-->

		<div class="container-wpl">
			<!-- Logo von Schrauben-Jäger / Werkzeug-Jäger -->
			<h2>Arbeitsplatz WPL003</h2>

			<p>Folgende Gegenstaende wurden empfangen:</p>

			<div class='items-wpl-1'>
				<div id="item1" class="item">
					<input type="checkbox" id="item1-check">
					<h4>1x</h4>
					<label for="item1-check"> Jabra 2 Evolve 65</label>
					<h3> #3556</h3>
				</div>

				<div id="item2" class="item">
					<input type="checkbox" id="item2-check">
					<h4>1x</h4>
					<label for="item2-check">NBSJ3410</label>
					<h3> #3557</h3>
				</div>
			</div>

				<!-- Content -->
			<div class="unterschrift">
				<div class="row">
					<p>Geprueft und bestaetigt von David Lowicki</p>
				</div>
				<div class="row">
					<div class="col-md-12">
		 				<canvas id="sig-canvas" width="620" height="160">
		 					Ihr Browser unterstüzt keine digitale Unterschrift!
		 				</canvas>
		 			</div>
				</div>
				<div class="row">
					<div class="">
						<button class="btn-remove" id="sig-clearBtn">Unterschrift loeschen</button>
					</div>
				</div>
	</div>

		</div>


		<script>
			(function() {
  window.requestAnimFrame = (function(callback) {
    return window.requestAnimationFrame ||
      window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.oRequestAnimationFrame ||
      window.msRequestAnimaitonFrame ||
      function(callback) {
        window.setTimeout(callback, 1000 / 60);
      };
  })();

  var canvas = document.getElementById("sig-canvas");
  var ctx = canvas.getContext("2d");
  ctx.strokeStyle = "#222222";
  ctx.lineWidth = 4;

  var drawing = false;
  var mousePos = {
    x: 0,
    y: 0
  };
  var lastPos = mousePos;

  canvas.addEventListener("mousedown", function(e) {
    drawing = true;
    lastPos = getMousePos(canvas, e);
  }, false);

  canvas.addEventListener("mouseup", function(e) {
    drawing = false;
  }, false);

  canvas.addEventListener("mousemove", function(e) {
    mousePos = getMousePos(canvas, e);
  }, false);

  // Add touch event support for mobile
  canvas.addEventListener("touchstart", function(e) {

  }, false);

  canvas.addEventListener("touchmove", function(e) {
    var touch = e.touches[0];
    var me = new MouseEvent("mousemove", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(me);
  }, false);

  canvas.addEventListener("touchstart", function(e) {
    mousePos = getTouchPos(canvas, e);
    var touch = e.touches[0];
    var me = new MouseEvent("mousedown", {
      clientX: touch.clientX,
      clientY: touch.clientY
    });
    canvas.dispatchEvent(me);
  }, false);

  canvas.addEventListener("touchend", function(e) {
    var me = new MouseEvent("mouseup", {});
    canvas.dispatchEvent(me);
  }, false);

  function getMousePos(canvasDom, mouseEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
      x: mouseEvent.clientX - rect.left,
      y: mouseEvent.clientY - rect.top
    }
  }

  function getTouchPos(canvasDom, touchEvent) {
    var rect = canvasDom.getBoundingClientRect();
    return {
      x: touchEvent.touches[0].clientX - rect.left,
      y: touchEvent.touches[0].clientY - rect.top
    }
  }

  function renderCanvas() {
    if (drawing) {
      ctx.moveTo(lastPos.x, lastPos.y);
      ctx.lineTo(mousePos.x, mousePos.y);
      ctx.stroke();
      lastPos = mousePos;
    }
  }

  // Prevent scrolling when touching the canvas
  document.body.addEventListener("touchstart", function(e) {
    if (e.target == canvas) {
      e.preventDefault();
    }
  }, false);
  document.body.addEventListener("touchend", function(e) {
    if (e.target == canvas) { e.preventDefault(); } }, false);
  document.body.addEventListener("touchmove", function(e) {
    if (e.target == canvas) { e.preventDefault(); } }, false);

  (function drawLoop() { requestAnimFrame(drawLoop); renderCanvas(); })();

  function clearCanvas() { canvas.width = canvas.width; }

  // Set up the UI
  var sigText = document.getElementById("sig-dataUrl");
  var sigImage = document.getElementById("sig-image");
  var clearBtn = document.getElementById("sig-clearBtn");
  var submitBtn = document.getElementById("sig-submitBtn");
  clearBtn.addEventListener("click", function(e) {
    clearCanvas();
    sigText.innerHTML = "Data URL for your signature will go here!";
    sigImage.setAttribute("src", "");
  }, false);
  submitBtn.addEventListener("click", function(e) {
    var dataUrl = canvas.toDataURL();
    sigText.innerHTML = dataUrl;
    sigImage.setAttribute("src", dataUrl);
  }, false);

})();
		</script>

	</body>
</html>