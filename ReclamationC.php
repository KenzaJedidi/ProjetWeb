<?php
	include_once dirname(__FILE__).'/../Config.php';
	include_once dirname(__FILE__).'/../Model/Reclamation.php';

    class ReclamationC {

        /////..............................Afficher............................../////
                function AfficherReclamation(){
                    $sql="SELECT * FROM Reclamation";
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
                function SupprimerReclamation($idReclamation){
                    $sql="DELETE FROM Reclamation WHERE idReclamation=:idReclamation";
                    $db = config::getConnexion();
                    $req=$db->prepare($sql);
                    $req->bindValue(':idReclamation', $idReclamation);   
                    try{
                        $req->execute();
                    }
                    catch(Exception $e){
                        die('Erreur:'. $e->getMeesage());
                    }
                }
        
        
        /////..............................Ajouter............................../////
                function AjouterReclamation($Reclamation){
                    $sql="INSERT INTO Reclamation (idUser,Type,Message,statut) 
                    VALUES (:idUser,:Type,:Message,:statut)";
                    
                    $db = config::getConnexion();
                    try{
                        $query = $db->prepare($sql);
                        $query->execute([
                            'idUser' => $Reclamation->getidUser(),
                            'Type' => $Reclamation->getType(),
                            'Message' => $Reclamation->getMessage(),
                            'statut' => $Reclamation->getstatut(),
                    ]);
                                
                    }
                    catch (Exception $e){
                        echo 'Erreur: '.$e->getMessage();
                    }			
                }
        /////..............................Affichage par la cle Primaire............................../////
                function RecupererReclamation($idReclamation){
                    $sql="SELECT * from Reclamation where idReclamation=$idReclamation";
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
                function ModifierReclamation($Reclamation,$idReclamation){
                    try {
                        $db = config::getConnexion();
                $query = $db->prepare('UPDATE Reclamation SET  idUser = :idUser, Type = :Type, Message = :Message , statut = :statut  WHERE idReclamation= :idReclamation');
                        $query->execute([
                            'idUser' => $Reclamation->getidUser(),
                            'Type' => $Reclamation->getType(),
                            'Message' => $Reclamation->getMessage(),
                            'statut' => $Reclamation->getstatut(),
                            'idReclamation' => $idReclamation
                        ]);
                        echo $query->rowCount() . " UPDATED successfully <br>";
                    } catch (PDOException $e) {
                        $e->getMessage();
                    }
                }

                function UpdateStatutReclamation($idReclamation, $statut) {
                    try {
                        $db = config::getConnexion();
                        $query = $db->prepare('UPDATE Reclamation SET statut = :statut WHERE idReclamation = :idReclamation');
                        $query->execute([
                            'statut' => $statut,
                            'idReclamation' => $idReclamation
                        ]);
                    } catch (PDOException $e) {
                        die('Erreur: ' . $e->getMessage());
                    }
                }
                
            }


?>