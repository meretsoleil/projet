<html>
	<head>
		<link rel="stylesheet" href="style.css" type="text/css" />
		 <title>Location</title>
		 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		 <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		 <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
		 <link rel="stylesheet" href="/resources/demos/style.css" />
		 <script>
			 <?php /*Creer autant de variable qu'il faut de calendrier */ ?>
			 $(function() {
			  $( "#datepicker1" ).datepicker();
			  $( "#datepicker2" ).datepicker();
			 });
		 </script>
	 </head>
	<body bgcolor='beige'>
		<div id="corps">
			<h1><center> MER ET SOLEIL : Service de Location</center></h1></br></br>
			
			
			<?php
				if ((!isset($_POST['nomclient'])) && (!isset($_POST['TypeAp'])) )
					{ 
			?>
						Veuillez remplir ce formulaire : </br></br>
						<form action="" method="post">
						Votre nom <input type="text" size="50" name="nomclient"> </br></br>
						Quelle taille d'appartement voulez vous? </br>
						T1 <input type ="radio" name ="TypeAp" value ="1">
						T2 <input type ="radio" name ="TypeAp" value ="2">
						T3 <input type ="radio" name ="TypeAp" value ="3">
						T4 <input type ="radio" name ="TypeAp" value ="4">
						T5 <input type ="radio" name ="TypeAp" value ="5">
						T6 <input type ="radio" name ="TypeAp" value ="6"></br></br>
						<input type="submit" />
						</form>
			<?php
					}
			?>


			<?php
				if ((isset($_POST['nomclient'])) && (isset($_POST['TypeAp'])) )
					{
					if ((!empty($_POST['nomclient'])) && (!empty($_POST['TypeAp'])) )
						{
			?>
							<h2>Bonjour Mr/Mme <?php $nom = $_POST['nomclient'];echo $nom;?>. Voici la liste des appartements du type <?php $typeapp = $_POST['TypeAp'];echo $typeapp;?> : </h2></br></br>
							
			<?php
							//connection a la base
							include("config.php");
							
							// Verification : Si le client existe deja, on ne l'ajoute pas une deuxieme fois
							$nomexistant = mysql_query("SELECT NomLocataire FROM locataire") or die (mysql_error());
							$existe = 0;
							while ($donnees = mysql_fetch_array($nomexistant)) 
								{
									if ($nom == $donnees['NomLocataire'])
										{
											$existe = 1;
										}
								}
							
							// si c'est un nouveau client, on l'ajoute a la base de données
							if ( $existe == 0)
								{
									mysql_query("INSERT INTO locataire VALUES ('','$_POST[nomclient]')");
								}
								
							$nomclient = "$_POST[nomclient]";
							
							$reponses = mysql_query("SELECT * FROM typeappart, tarif, appartement where typeappart.TypeAppart = $_POST[TypeAp] and appartement.TypeAppart = typeappart.TypeAppart and appartement.NumAppart=tarif.NumAppart") or die(mysql_error());
							
			
							while ($donnees = mysql_fetch_array($reponses) )
							{
								echo '<b>Numero appartement:</b>  '  .$donnees['NumAppart']. '  '; 
								echo '<b>Prix hebdomadaire</b>:  '   .$donnees['PrixHebdo']. '<br></br>'; 
							}

			?> 
							</br></br>
							<form action="" method="post">
								<input type="hidden" name="nomclient" value="<?php echo $nomclient;?>">
								<?php   
									$sql = "SELECT * FROM typeappart, tarif, appartement where typeappart.TypeAppart = $_POST[TypeAp] and appartement.TypeAppart = typeappart.TypeAppart and appartement.NumAppart=tarif.NumAppart"; 
									$result = mysql_query($sql) 
								?> 
									<p> Veuillez choisir votre appartement: <select size="1" name="numappart"> 
								<?php 
									while ($row=mysql_fetch_array($result)) 
										{ 
								?> 
											<OPTION><?php echo $row['NumAppart']; ?></OPTION> 
								<?php 
										}
								?>
								<input type="submit" > 
							</form>

							 <?php
						}
					}
			 ?>

			 <?php
			 if ((isset($_POST['numappart'])) && (!isset($_POST['valide'])))
				{
					//on teste si tous les champs du formulaire sont remplits
					if ((!empty($_POST['numappart'])) )
						{
			?>
							<h2>Bonjour Mr/Mme <?php $nom = $_POST['nomclient'];echo $nom;?>.<?php $typeapp = $_POST['TypeAp'];echo $typeapp;?> : </h2></br></br>
							
			<?php
							$nomclient = $_POST['nomclient'];
							$numappart = $_POST['numappart'];
						
							
							//connection a la base
							include("config.php"); ?>
								
							Vous avez choisi l'appartement numero : <?php echo $numappart?> </br></br>

							<?php
							$num="$_POST[numappart]";
							$requete = "select PrixHebdo from tarif where NumAppart = '".$num."'";
							$resultat = mysql_query ($requete);
							$pxheb = mysql_fetch_object($resultat);
							$xxx = $pxheb->PrixHebdo;
							?>

							Cet appartement coute : <?php echo $xxx?>  Euros</br></br>
							<?php $montantacpte = $xxx *0.3;?>
							Le montant de l'acompte est de : <?PHP echo $montantacpte;?>  Euros</br></br>


							<form action="" name="date1" id="date1" method="post">
							<input type="hidden" name="nomclient" value="<?php echo $nomclient;?>">
							<input type="hidden" name="numappart" value="<?php echo $numappart;?>">
							<input type="hidden" name="montantlocation" value="<?php echo $xxx;?>">
							<input type="hidden" name="valide" value="1">
							<input type="hidden" name="montantacpte" value="<?php echo $montantacpte;?>">
							<p>Date deb loc: <input type="text" id="datepicker1" name="datedebloc" /></p>
							<p>Date fin loc: <input type="text" id="datepicker2" name="datefinloc" /></p>
							<input type="submit" value="Valider" >
							</form>
							<!-- <a href="payement.php" onclick="javascript:document.getElementById('date1').submit();"><b>Paiement de l'acompte</b></a> -->
							
							<?php
						 }
				}
			?>
			<?php
			if ((isset($_POST['datedebloc'])) && (isset($_POST['datefinloc'])) )
				{
						
					if ((!empty($_POST['datedebloc'])) && (!empty($_POST['datefinloc'])))
						{
						
						//connection a la base
						include("config.php");

						$nomclient = $_POST['nomclient'];
						$numappart = $_POST['numappart'];
						$montantlocation = $_POST['montantlocation'];
						$montantacpte = $_POST['montantacpte'];

						$date = date('D d/m/Y');	
						$requete = "select IDLocataire from locataire where NomLocataire = '".$nomclient."'";
						$resultat = mysql_query ($requete);
						$idloc = mysql_fetch_object($resultat);
						$idlocataire = $idloc->IDLocataire;
							
						mysql_query("INSERT INTO location VALUES ('','$_POST[datedebloc]','$_POST[datefinloc]','$montantacpte','$date','$montantlocation','$numappart','$idlocataire')");
						
					?>
					</br>
					<h2>Bonjour Mr/Mme <?php $nom = $_POST['nomclient'];echo $nom;?>. Il ne vous reste plus qu'a payer l'acompte s'il vous plait. <?php $typeapp = $_POST['TypeAp'];echo $typeapp;?> : </h2></br></br>
					<a href="payement.php">Payement de l'accompte</a>
					<?php
					}
				}

			   ?>
			   
			</br><center><a href='Mer et soleil.html'> Retour </a></center>
		</div>
	</body>
</html>