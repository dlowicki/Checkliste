<?php
require_once('sql.php');
?>
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
		if(!isset($_GET['wplUser']))
		{
			echo '<form method="GET">';
			echo '<p style="margin-bottom: 5px">Vorname.Nachname eingeben</p>';
            echo '<input type="text" name="wplUser" placeholder="Vorname.Nachname"><br>';
			echo '<input type="submit" id="btn-wplid" value="Suchen">';
			echo '</form>';
			return;
		}

        $wplUser = $_GET['wplUser'];

        //if($wplUser != null && $wplUser != "" && strlen($wplUser) > 0 && strpos($wplUser, '.')){}
            //$wplID = (int) filter_var($_GET['wplID'], FILTER_SANITIZE_NUMBER_INT);

		$wplUserSplitted = explode('.',$wplUser);
		$userID = getUserID($wplUserSplitted[0],$wplUserSplitted[1]);		
		$assets = getAssetFromWorkplace($userID);
		

        ?>
		<div class="container-wpl">
			<!-- Logo von Schrauben-Jäger / Werkzeug-Jöger -->
			<h2>Arbeitsplatz von <?php echo $wplUser; ?></h2>

			<p>Hardware markieren, die erhalten wurde:</p>
			
			<div class='items-wpl-1'>
				<?php
				$iCount = 0;
				foreach($assets as $a){
					echo '<div id="item'.$iCount.'" class="item">';
					echo '<input type="checkbox" id="item'.$iCount.'-check">';
					echo '<h4>1x</h4>';
					echo '<label for="item'.$iCount.'-check" id="item'.$iCount.'-object">'.$a["Name"].'</label>';
					echo '<h3 id="item'.$iCount.'-inventar">#'.$a["in"].'</h3>';
					echo '</div>';
					$iCount++;
				}
				?>
			</div>

				<!-- Content -->
			<div class="unterschrift">
				<div class="row"> <p>Geprüft und bestätigt von <span id="wplBearbeiter"><?php echo $wplUserSplitted[0] . ' ' . $wplUserSplitted[1] ?></span></p> </div>
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
		  
		  console.log(items)

          // Bevor PDF generiert werden kann, Daten vergleichen mit den Daten die von Matrix42 gesendet wurden ( zwecks Manipulation der Daten )
          $.ajax({
            url: "pdf.php",
            method: "POST",
            data: {jSign: dataUrl, jBearbeiter: wplBearbeiter, jData: items},
            success: function(result) {
                console.log(result);
            }
          });
        }, false);
      })();
		</script>

	</body>
</html>