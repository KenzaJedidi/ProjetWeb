<?php
	include_once dirname(__FILE__).'/../Config.php';
	include_once dirname(__FILE__).'/../Model/BonPlan.php';

    class BonPlanC {

        /////..............................Afficher............................../////
        function AfficherBonPlan() {
            $sql = "SELECT * FROM bonplan";
            $db = config::getConnexion();
            try {
                $liste = $db->query($sql);
                return $liste->fetchAll(); // <- transforme en tableau
            } catch (Exception $e) {
                die('Erreur:' . $e->getMessage());
            }
        }
        
        
        /////..............................Supprimer............................../////
                function SupprimerBonPlan($idBonPlan){
                    $sql="DELETE FROM BonPlan WHERE idBonPlan=:idBonPlan";
                    $db = config::getConnexion();
                    $req=$db->prepare($sql);
                    $req->bindValue(':idBonPlan', $idBonPlan);   
                    try{
                        $req->execute();
                    }
                    catch(Exception $e){
                        die('Erreur:'. $e->getMeesage());
                    }
                }
        
        
        /////..............................Ajouter............................../////
                function AjouterBonPlan($BonPlan){
                    $sql="INSERT INTO BonPlan (destination,restaurant,hotel) 
                    VALUES (:destination,:restaurant,:hotel)";
                    
                    $db = config::getConnexion();
                    try{
                        $query = $db->prepare($sql);
                        $query->execute([
                            'destination' => $BonPlan->getdestination(),
                            'restaurant' => $BonPlan->getrestaurant(),
                            'hotel' => $BonPlan->gethotel(),
                    ]);
                                
                    }
                    catch (Exception $e){
                        echo 'Erreur: '.$e->getMessage();
                    }			
                }
        /////..............................Affichage par la cle Primaire............................../////
                function RecupererBonPlan($idBonPlan){
                    $sql="SELECT * from BonPlan where idBonPlan=$idBonPlan";
                    $db = config::getConnexion();
                    try{
                        $query=$db->prepare($sql);
                        $query->execute();
        
                        $Reclamation=$query->fetch();
                        return $Reclamation;
                    }
                    catch (Exception $e){
                        die('Erreur: '.$e->getMessage());
                    }
                }
        
        /////..............................Update............................../////
                function ModifierBonPlan($BonPlan,$idBonPlan){
                    try {
                        $db = config::getConnexion();
                $query = $db->prepare('UPDATE BonPlan SET  destination = :destination, restaurant = :restaurant, hotel = :hotel  WHERE idBonPlan= :idBonPlan');
                        $query->execute([
                            'destination' => $BonPlan->getdestination(),
                            'restaurant' => $BonPlan->getrestaurant(),
                            'hotel' => $BonPlan->gethotel(),
                            'idBonPlan' => $idBonPlan
                        ]);
                        echo $query->rowCount() . " UPDATED successfully <br>";
                    } catch (PDOException $e) {
                        $e->getMessage();
                    }
              
              
                }   
                

                  /////..............................rechercher............................../////
                function RechercherBonPlanParDestination($destination) {
                    $sql = "SELECT * FROM BonPlan WHERE destination LIKE :destination";
                    $db = config::getConnexion();
                    try {
                        $query = $db->prepare($sql);
                        $query->execute([
                            'destination' => '%' . $destination . '%'
                        ]);
                        return $query->fetchAll();
                    } catch (Exception $e) {
                        die('Erreur: ' . $e->getMessage());
                    }
                }
            
                





                


            }





?>