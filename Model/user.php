<?php
class User {
    private $id_user;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role;
    private $tel;
    private $profile_picture;
    private $created_at; // Add this property
    
    public function __construct($id_user = null, $nom = null, $prenom = null, $email = null, 
                                $password = null, $role = null, $tel = null, $profile_picture = null, 
                                $created_at = null) {
        $this->id_user = $id_user;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        // Hash password if it's not already hashed
        $this->password = (strlen($password) < 60) ? password_hash($password, PASSWORD_DEFAULT) : $password;
        $this->role = $role;
        $this->tel = $tel;
        $this->profile_picture = $profile_picture;
        $this->created_at = $created_at;
    }
    
    // Getters
    public function getIdUser(): int {
        return $this->id_user;
    }
    
    public function getNom(): string {
        return $this->nom;
    }
    
    public function getPrenom(): string {
        return $this->prenom;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function getPassword(): string {
        return $this->password;
    }
    
    public function getRole(): string {
        return $this->role;
    }
    
    public function getTel(): string {
        return $this->tel;
    }
    
    public function getProfilePicture() {
        return $this->profile_picture;
    }
    
    public function getCreatedAt() {
        return $this->created_at ?? date('Y-m-d H:i:s');
    }
    
    // Setters
    public function setIdUser(int $id_user): void {
        $this->id_user = $id_user;
    }
    
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    
    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }
    
    public function setEmail(string $email): void {
        $this->email = $email;
    }
    
    public function setPassword(string $password): void {
        $this->password = $password;
    }
    
    public function setRole(string $role): void {
        $this->role = $role;
    }
    
    public function setTel(string $tel): void {
        $this->tel = $tel;
    }
    
    public function setProfilePicture($profile_picture): void {
        $this->profile_picture = $profile_picture;
    }
    
    // Simple password check
    public function checkPassword(string $inputPassword): bool {
        return password_verify($inputPassword, $this->password);
    }
}
?>