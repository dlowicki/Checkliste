<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="style.css" /> 
		<title>Checkliste</title>
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
	</head>
	<body>
        <?php
		if(!isset($_GET['wplID']))
		{
			echo '<form method="GET">';
			echo '<input type="text" name="wplID" placeholder="ArbeitsplatzID...">';
            echo '<span> oder </span>';
            echo '<input type="text" name="userID" placeholder="Mitarbeiter-Name..."><br>';
			echo '<input type="submit" id="btn-wplid" value="Suchen">';
			echo '</form>';
			return;
		}

        $wplID = $_GET['wplID'];
        $userID = $_GET['userID'];

        // Wenn WPLID gesetzt ist
        if($wplID != null && $wplID != "" && strlen($wplID) > 0){
            //wplID ist gesetzt
            $wplID = (int) filter_var($_GET['wplID'], FILTER_SANITIZE_NUMBER_INT);
            // Wenn Länge von ID kleiner als 2 => Abbrechen und zurücksetzen
            if(strlen($wplID) < 2) { header('Location: index.php'); return; }
            // Falls Benutzername gesetzt wurde, checken ob WPLID mit Benutzername passt
            // Suche in Datenbank nach WPLID
        }

        // Wenn UserID gesetzt ist
        if($userID != null && $userID != "" && strlen($userID) > 0){
            // Falls WPLID gesetzt wurde, checken ob WPLID mit Benutzername passt
            // Suche in Datenbank nach WPLID
        }



        // Überprüfen, ob ID in Matrix Datenbank existiert und ggf. Geräte aus Datenbank ziehen
        // Wenn Geräte aus Datenbank kleiner als 1 => Ausgeben, dass keine Geräte für diesen Arbeitsplatz registriert sind


        ?>
		<div class="container-wpl">
			<!-- Logo von Schrauben-Jäger / Werkzeug-Jöger -->
			<h2>Arbeitsplatz WPL<?php echo $wplID ?></h2>

			<p>Bitte die Gegenstände markieren, die empfangen wurden:</p>

			<div class='items-wpl-1'>
				<div id="item0" class="item">
					<input type="checkbox" id="item0-check">
					<h4 id="item0-amount">1x</h4>
					<label for="item0-check" id="item0-object"> Jabra 2 Evolve 65</label>
					<h3 id="item0-inventar">#3556</h3>
				</div>

				<div id="item1" class="item">
					<input type="checkbox" id="item1-check">
					<h4 id="item1-amount">1x</h4>
					<label for="item1-check" id="item1-object">NBSJ3410</label>
					<h3 id="item1-inventar"> #3557</h3>
				</div>
			</div>

				<!-- Content -->
			<div class="unterschrift">
				<div class="row"> <p>Geprüft und bestätigt von <span id="wplBearbeiter">David Lowicki</span></p> </div>
				<div class="row">
		 			<canvas id="sig-canvas" width="620" height="160">
		 				Ihr Browser unterstüzt keine digitale Unterschrift!
		 			</canvas>
				</div>
				<div class="row-btn">
					<div class="">
						<button class="sig-btn" id="sig-sendData">Bestätigen</button>
						<button class="sig-btn" id="sig-clearBtn">Unterschrift löschen</button>
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

        canvas.addEventListener("mousedown", function(e) { drawing = true; lastPos = getMousePos(canvas, e); }, false);

        canvas.addEventListener("mouseup", function(e) { drawing = false; }, false);

        canvas.addEventListener("mousemove", function(e) { mousePos = getMousePos(canvas, e); }, false);

        // Add touch event support for mobile
        canvas.addEventListener("touchstart", function(e) { }, false);

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

        canvas.addEventListener("touchend", function(e) { var me = new MouseEvent("mouseup", {}); canvas.dispatchEvent(me); }, false);

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

        // Das zeichnen in der Canvas !!Wichtig!!
        function renderCanvas() {
          if (drawing) { ctx.moveTo(lastPos.x, lastPos.y); ctx.lineTo(mousePos.x, mousePos.y); ctx.stroke(); lastPos = mousePos; }
        }

        // Wenn im Canvas gezeichnet wird, wird die Funktion "scrollen" deaktiviert
        document.body.addEventListener("touchstart", function(e) {
          if (e.target == canvas) { e.preventDefault(); }
        }, false);
        document.body.addEventListener("touchend", function(e) {
          if (e.target == canvas) { e.preventDefault(); } }, false);
        document.body.addEventListener("touchmove", function(e) {
          if (e.target == canvas) { e.preventDefault(); } }, false);

        (function drawLoop() { requestAnimFrame(drawLoop); renderCanvas(); })();

        function clearCanvas() { canvas.width = canvas.width; }

        var clearBtn = document.getElementById("sig-clearBtn");
        var submitBtn = document.getElementById("sig-sendData");
        // Unterschrift löschen anklicken = Canvas wird gecleart
        clearBtn.addEventListener("click", function(e) { clearCanvas(); }, false);
        // Bestätigen anklicken = Daten werden an pdf.php versendet
        submitBtn.addEventListener("click", function(e) {
          var dataUrl = canvas.toDataURL();
          var wplID = "<?php echo $wplID; ?>";
          var wplBearbeiter = $('#wplBearbeiter').text();

          var items = [];
          $('.item').each(function(index){
            var check = $('#item'+index+'-check').is(':checked');
            var amount = $('#item'+index+'-amount').text();
            var object = $('#item'+index+'-object').text();
            var inv = $('#item'+index+'-inventar').text();
            var temp = [check,amount,object,inv];
            items.push(temp);
          });

          items = JSON.stringify(items);

          // Bevor PDF generiert werden kann, Daten vergleichen mit den Daten die von Matrix42 gesendet wurden ( zwecks Manipulation der Daten )
          $.ajax({
            url: "pdf.php",
            method: "POST",
            data: {jSign: dataUrl, jID: wplID, jBearbeiter: wplBearbeiter, jData: items},
            success: function(result) {
                console.log(result);
            }
          });
        }, false);
      })();
		</script>

	</body>
</html>