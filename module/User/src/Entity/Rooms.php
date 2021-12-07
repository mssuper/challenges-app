<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Esta classe representa uma sala registrada.
 * @ORM\Entity(repositoryClass="\User\Repository\RoomsRepository")
 * @ORM\Table(name="rooms")
 */
class Rooms
{
    // Constantes de status do usuário.
    const STATUS_ACTIVE = 1; // Active user.
    const STATUS_INACTIVE = 2; // Retired user.

    /**
     * @ORM\Id
     * @ORM\Column(name="idroom")
     * @ORM\GeneratedValue
     */
    protected $idroom;

    /**
     * @ORM\Column(name="area")
     */
    protected $area;

    /**
     * @ORM\Column(name="room_name")
     */
    protected $room_name;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

    /**
     * Retorna o ID do usuário.
     * @return integer
     */
    public function getId()
    {
        return $this->idroom;
    }

    /**
     * Define o ID do usuário.
     * @param int $idroom
     */
    public function setId($idroom)
    {
        $this->idroom = $idroom;
    }

    /**
     * Retorna Área.
     * @return integer
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Define e-mail.
     * @param string $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * Retorna o nome da Sala.
     * @return string
     */
    public function getRoomName()
    {
        return $this->room_name;
    }

    /**
     * Define o nome da Sala.
     * @param string $room_name
     */
    public function setRoomName($room_name)
    {
        $this->room_name = $room_name;
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
     * Define o status.
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
     * Retorna possíveis status como array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inativo'
        ];
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
}



