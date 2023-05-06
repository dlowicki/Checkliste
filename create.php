<?php
	// HIER IP's freigeben
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
			if(isset($_GET['wplUser']))
            {
                $user = $_GET['wplUser'];
				if(sizeof(explode('.',$user)) == 2)
                {
                    if(file_exists("data\\$user.txt"))
                    {
                        echo '<a href="create.php" class="nav-button">Startseite</a>';
                        echo 'Schluessel wurde schon generiert!<br>';
                        echo 'Soll der Schluessel fuer '.$user.' entfernt werden?<br>';
                        echo '<a href="index.php">Zurueck</a>';
                        echo '<form action="create.php" method="POST">
                                <input type="hidden" value="'.$user.'" name="hidden-key">
                                <input type="submit" name="btn-delete" value="Entfernen"></form>';
						return;
                    }

					$uuid = generateRandomString();
                    $file = fopen("data\\$user.txt",'w');
                    fwrite($file,$uuid);
                    fclose($file);

                    echo "Schluessel wurde generiert: <br><b>" . $uuid . "</b><br> bitte an <b>" . $user . "</b> uebergeben.";
                    return;
                }
            } elseif(isset($_POST['btn-delete']) && isset($_POST['hidden-key'])) {
                unlink("data\\".$_POST['hidden-key'] . ".txt");
                echo "Schluessel fuer " . $_POST['hidden-key'] . ' wurde entfernt<br>';
                echo '<a href="create.php">Reload</a>';
                return;
            } else {
                echo '<a href="index.php" class="nav-button">Startseite</a>';
                echo '<form action="create.php" method="GET">
		                <p style="margin-bottom: 5px">Vorname.Nachname eingeben</p>
                        <input type="text" name="wplUser" placeholder="Vorname.Nachname"><br>
		                <input type="submit" id="btn-wplid" name="btn-wplid" value="Erstellen">
		                </form>';
            }

			function generateRandomString($length = 20) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[random_int(0, $charactersLength - 1)];
                }
                return $randomString;
            }
        ?>
	</body>
</html>