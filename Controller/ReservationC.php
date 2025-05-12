<?php
	include_once dirname(__FILE__).'/../Config.php';
	include_once dirname(__FILE__).'/../Model/Reservation.php';

    class ReservationC {

        /////..............................Afficher............................../////
                function AfficherReservation(){
                    $sql="SELECT * FROM Reservation";
                    $db = config::getConnexion();
                    try{
                        $query = $db->query($sql);
                        $liste = $query->fetchAll(PDO::FETCH_ASSOC);
                        return $liste;
                    }
                    catch(Exception $e){
                        die('Erreur:'. $e->getMessage());
                    }
                }
        
        /////..............................Supprimer............................../////
                function SupprimerReservation($idReservation){
                    $sql="DELETE FROM Reservation WHERE idReservation=:idReservation";
                    $db = config::getConnexion();
                    $req=$db->prepare($sql);
                    $req->bindValue(':idReservation', $idReservation);   
                    try{
                        $req->execute();
                    }
                    catch(Exception $e){
                        die('Erreur:'. $e->getMeesage());
                    }
                }
        
        
        /////..............................Ajouter............................../////
                function AjouterReservation($Reservation){
                    $sql="INSERT INTO Reservation (idBonPlan,dateDepart,dateRetour,nbPersonne,commentaire,statut) 
                    VALUES (:idBonPlan,:dateDepart,:dateRetour,:nbPersonne,:commentaire,:statut)";
                    
                    $db = config::getConnexion();
                    try{
                        $query = $db->prepare($sql);
                        $query->execute([
                            'idBonPlan' => $Reservation->getidBonPlan(),
                            'dateDepart' => $Reservation->getdateDepart(),
                            'dateRetour' => $Reservation->getdateRetour(),
                            'nbPersonne' => $Reservation->getnbPersonne(),
                            'commentaire' => $Reservation->getcommentaire(),
                            'statut' => $Reservation->getstatut(),
                    ]);
                                
                    }
                    catch (Exception $e){
                        echo 'Erreur: '.$e->getMessage();
                    }			
                }
        /////..............................Affichage par la cle Primaire............................../////
                function RecupererReservation($idReservation){
                    $sql="SELECT * from Reservation where idReservation=$idReservation";
                    $db = config::getConnexion();
                    try{
                        $query=$db->prepare($sql);
                        $query->execute();
        
                        $Reservation=$query->fetch();
                        return $Reservation;
                    }
                    catch (Exception $e){
                        die('Erreur: '.$e->getMessage());
                    }
                }
        
        /////..............................Update............................../////
                function ModifierReservation($Reservation,$idReservation){
                    try {
                        $db = config::getConnexion();
                $query = $db->prepare('UPDATE Reservation SET  idBonPlan = :idBonPlan, dateDepart = :dateDepart, dateRetour = :dateRetour, nbPersonne = :nbPersonne, commentaire = :commentaire, statut = :statut  WHERE idReservation= :idReservation');
                        $query->execute([
                            'idBonPlan' => $Reservation->getidBonPlan(),
                            'dateDepart' => $Reservation->getdateDepart(),
                            'dateRetour' => $Reservation->getdateRetour(),
                            'nbPersonne' => $Reservation->getnbPersonne(),
                            'commentaire' => $Reservation->getcommentaire(),
                            'statut' => $Reservation->getstatut(),
                            'idReservation' => $idReservation
                        ]);
                        echo $query->rowCount() . " UPDATED successfully <br>";
                    } catch (PDOException $e) {
                        $e->getMessage();
                    }
                }                
                
        /////..............................Update Status............................../////
                function UpdateReservationStatus($idReservation, $newStatus){
                    try {
                        $db = config::getConnexion();
                        $query = $db->prepare('UPDATE Reservation SET statut = :statut WHERE idReservation = :idReservation');
                        $query->execute([
                            'statut' => $newStatus,
                            'idReservation' => $idReservation
                        ]);
                        return $query->rowCount() > 0;
                    } catch (PDOException $e) {
                        echo 'Erreur: '.$e->getMessage();
                        return false;
                    }
                }
            }


?>