<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Esta classe representa um usuário registrado.
 * @ORM\Entity(repositoryClass="\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User 
{
    // Constantes de status do usuário.
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="email")  
     */
    protected $email;
    
    /** 
     * @ORM\Column(name="full_name")  
     */
    protected $fullName;

    /** 
     * @ORM\Column(name="password")  
     */
    protected $password;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
        
    /**
     * @ORM\Column(name="pwd_reset_token")  
     */
    protected $passwordResetToken;
    
    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")  
     */
    protected $passwordResetTokenCreationDate;
    
    /**
     * Retorna o ID do usuário.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Define o ID do usuário.
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Retorna e-mail.
     * @return string
     */
    public function getEmail() 
    {
        return $this->email;
    }

    /**
     * Define e-mail.
     * @param string $email
     */
    public function setEmail($email) 
    {
        $this->email = $email;
    }
    
    /**
     * Retorna o nome completo.
     * @return string     
     */
    public function getFullName() 
    {
        return $this->fullName;
    }       

    /**
     * Define o nome completo.
     * @param string $fullName
     */
    public function setFullName($fullName) 
    {
        $this->fullName = $fullName;
    }
    
    /**
     * Retorna o status.
     * @return int     
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * Retorna possíveis status como array.
     * @return array
     */
    public static function getStatusList() 
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }    
    
    /**
     * Retorna o status do usuário como string
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];
        
        return 'Unknown';
    }    
    
    /**
     * Define o status.
     * @param int $status     
     */
    public function setStatus($status) 
    {
        $this->status = $status;
    }   
    
    /**
     * Retorna a senha.
     * @return string
     */
    public function getPassword() 
    {
       return $this->password; 
    }
    
    /**
     * Define a senha.
     * @param string $password
     */
    public function setPassword($password) 
    {
        $this->password = $password;
    }
    
    /**
     * Retorna a data de criação do usuário.
     * @return string     
     */
    public function getDateCreated() 
    {
        return $this->dateCreated;
    }
    
    /**
     * Define a data em que este usuário foi criado.
     * @param string $dateCreated     
     */
    public function setDateCreated($dateCreated) 
    {
        $this->dateCreated = $dateCreated;
    }    
    
    /**
     * Retorna o token de redefinição de senha.
     * @return string
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }
    
    /**
     * Define o token de redefinição de senha.
     * @param string $token
     */
    public function setPasswordResetToken($token) 
    {
        $this->passwordResetToken = $token;
    }
    
    /**
     * Retorna a data de criação do token de redefinição de senha.
     * @return string
     */
    public function getPasswordResetTokenCreationDate()
    {
        return $this->passwordResetTokenCreationDate;
    }
    
    /**
     * Define a data de criação do token de redefinição de senha.
     * @param string $date
     */
    public function setPasswordResetTokenCreationDate($date) 
    {
        $this->passwordResetTokenCreationDate = $date;
    }
}



