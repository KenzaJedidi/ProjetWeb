<?php
	include_once dirname(__FILE__).'/../Config.php';
	include_once dirname(__FILE__).'/../Model/Reponse.php';

    class ReponseC {

        /////..............................Afficher............................../////
                function AfficherReponse(){
                    $sql="SELECT * FROM Reponse";
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
                function SupprimerReponse($idReponse){
                    $sql="DELETE FROM Reponse WHERE idReponse=:idReponse";
                    $db = config::getConnexion();
                    $req=$db->prepare($sql);
                    $req->bindValue(':idReponse', $idReponse);   
                    try{
                        $req->execute();
                    }
                    catch(Exception $e){
                        die('Erreur:'. $e->getMeesage());
                    }
                }
        
        
        /////..............................Ajouter............................../////
                function AjouterReponse($Reponse){
                    $sql="INSERT INTO Reponse (idReclamation,Message) 
                    VALUES (:idReclamation,:Message)";
                    
                    $db = config::getConnexion();
                    try{
                        $query = $db->prepare($sql);
                        $query->execute([
                            'idReclamation' => $Reponse->getidReclamation(),
                            'Message' => $Reponse->getMessage(),
                    ]);
                                
                    }
                    catch (Exception $e){
                        echo 'Erreur: '.$e->getMessage();
                    }			
                }
        /////..............................Affichage par la cle Primaire............................../////
                function RecupererReponse($idReponse){
                    $sql="SELECT * from Reponse where idReponse=$idReponse";
                    $db = config::getConnexion();
                    try{
                        $query=$db->prepare($sql);
                        $query->execute();
        
                        $Reponse=$query->fetch();
                        return $Reponse;
                    }
                    catch (Exception $e){
                        die('Erreur: '.$e->getMessage());
                    }
                }
        
        /////..............................Update............................../////
                function ModifierReponse($Reponse,$idReponse){
                    try {
                        $db = config::getConnexion();
                $query = $db->prepare('UPDATE Reponse SET  idReclamation = :idReclamation, Message = :Message WHERE idReponse= :idReponse');
                        $query->execute([
                            'idReclamation' => $Reponse->getidReclamation(),
                            'Message' => $Reponse->getMessage(),
                            'idReponse' => $idReponse, 
                        ]);
                        echo $query->rowCount() . " UPDATED successfully <br>";
                    } catch (PDOException $e) {
                        $e->getMessage();
                    }
                }
            }


?>