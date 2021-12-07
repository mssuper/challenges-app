<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Esta classe representa uma sala registrada.
 * @ORM\Entity(repositoryClass="\User\Repository\ScheduleRoomsRepository")
 * @ORM\Table(name="ScheduleRooms")
 */
class ScheduleRooms
{
    /**
     * @ORM\Id
     * @ORM\Column(name="idscheduleroom")
     * @ORM\GeneratedValue
     */
    protected $idscheduleroom;

    /**
     * @ORM\Column(name="datetime_in")
     */
    protected $datetime_in;

    /**
     * @ORM\Column(name="datetime_out")
     */
    protected $datetime_out;

    /**
     * @ORM\Column(name="iduser")
     */
    protected $iduser;

    /**
     * @ORM\Column(name="idroom")
     */
    protected $idroom;

    /**
     * Retorna o ID do usuário.
     * @return integer
     */
    public function getId()
    {
        return $this->idscheduleroom;
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
     * Retorna data de início.
     * @return datetime
     */
    public function getDatetimeIn()
    {
        return $this->datetime_in;
    }

    /**
     * Redefine a data de inicio.
     * @param datetime $datetime_in
     */
    public function setDatetimeIn($datetime_in)
    {
        $this->area = $datetime_in;
    }

    /**
     * Retorna o datetime final.
     * @return datetime
     */
    public function getDatetimeOut()
    {
        return $this->datetime_out;
    }

    /**
     * Define a datatime de saida.
     * @param datetime $datetime_out
     */
    public function setDatetimeOut($datetime_out)
    {
        $this->datetime_out = $datetime_out;
    }

    /**
     * Define o id do usuário.
     * @param integer $iduser
     */
    public function setIdUser($iduser)
    {
        $this->iduser = $iduser;
    }

    /**
     * Retorna o Id do usuário.
     * @return integer
     */
    public function getIdUser()
    {
        return $this->iduser;
    }

    /**
     * Define o Id da sala.
     * @param integer $idroom
     */
    public function setIdRoom($idroom)
    {
        $this->idroom = $idroom;
    }
    /**
     * Retorna o id da Sala.
     * @return integer
     */
    public function getIdRoom()
    {
        return $this->idroom;
    }

}



