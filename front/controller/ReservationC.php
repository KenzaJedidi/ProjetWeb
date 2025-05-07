<?php
    include_once dirname(__FILE__).'/../Config.php';
    include_once dirname(__FILE__).'/../Model/Reservation.php';

    class ReservationC {

        /////..............................Afficher............................../////
        function AfficherReservation(){
            $sql="SELECT * FROM reservation";
            $db = config::getConnexion();
            try{
                $liste = $db->query($sql);
                return $liste;
            }
            catch(Exception $e){
                die('Erreur:'. $e->getMessage());
            }
        }

        /////..............................Supprimer............................../////
        function SupprimerReservation($idReservation){
            $sql="DELETE FROM reservation WHERE idReservation=:idReservation";
            $db = config::getConnexion();
            $req=$db->prepare($sql);
            $req->bindValue(':idReservation', $idReservation);   
            try{
                $req->execute();
            }
            catch(Exception $e){
                die('Erreur:'. $e->getMessage());
            }
        }

        /////..............................Ajouter............................../////
        function AjouterReservation($Reservation){
            $sql="INSERT INTO reservation (idUser, Type, Lieu, Date, Details, Statut) 
                  VALUES (:idUser, :Type, :Lieu, :Date, :Details, :Statut)";
            
            $db = config::getConnexion();
            try{
                $query = $db->prepare($sql);
                $query->execute([
                    'idUser' => $Reservation->getidUser(),
                    'Type' => $Reservation->getType(),
                    'Lieu' => $Reservation->getLieu(),
                    'Date' => $Reservation->getDate(),
                    'Details' => $Reservation->getDetails(),
                    'Statut' => $Reservation->getStatut()
                ]);
            }
            catch (Exception $e){
                echo 'Erreur: '.$e->getMessage();
            }			
        }

        /////..............................Affichage par la cle Primaire............................../////
        function RecupererReservation($idReservation){
            $sql="SELECT * from reservation where idReservation=:idReservation";
            $db = config::getConnexion();
            try{
                $query=$db->prepare($sql);
                $query->bindValue(':idReservation', $idReservation);
                $query->execute();

                $Reservation=$query->fetch();
                return $Reservation;
            }
            catch (Exception $e){
                die('Erreur: '.$e->getMessage());
            }
        }

        /////..............................Update............................../////
        function ModifierReservation($Reservation, $idReservation){
            try {
                $db = config::getConnexion();
                $query = $db->prepare(
                    'UPDATE reservation SET 
                    idUser = :idUser, 
                    Type = :Type, 
                    Lieu = :Lieu, 
                    Date = :Date, 
                    Details = :Details, 
                    Statut = :Statut 
                    WHERE idReservation = :idReservation'
                );
                
                $query->execute([
                    'idUser' => $Reservation->getidUser(),
                    'Type' => $Reservation->getType(),
                    'Lieu' => $Reservation->getLieu(),
                    'Date' => $Reservation->getDate(),
                    'Details' => $Reservation->getDetails(),
                    'Statut' => $Reservation->getStatut(),
                    'idReservation' => $idReservation
                ]);
                
                return $query->rowCount();
            } catch (PDOException $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }

        /////..............................Update Statut............................../////
        function UpdateStatutReservation($idReservation, $Statut) {
            try {
                $db = config::getConnexion();
                $query = $db->prepare(
                    'UPDATE reservation SET Statut = :Statut 
                    WHERE idReservation = :idReservation'
                );
                $query->execute([
                    'Statut' => $Statut,
                    'idReservation' => $idReservation
                ]);
                return $query->rowCount();
            } catch (PDOException $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }

        /////..............................Rechercher............................../////
        function RechercherReservation($critere, $valeur) {
            $sql = "SELECT * FROM reservation WHERE $critere LIKE :valeur";
            $db = config::getConnexion();
            try {
                $query = $db->prepare($sql);
                $query->bindValue(':valeur', '%'.$valeur.'%');
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $e) {
                die('Erreur: ' . $e->getMessage());
            }
        }
    }
?>