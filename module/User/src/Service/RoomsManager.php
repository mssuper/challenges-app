<?php

namespace User\Service;

use User\Entity\Rooms;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Math\Rand;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;





/**
 * Este serviço é responsável por adicionar / editar usuários
 * e alteração da senha do usuário.
 **/
class RoomsManager
{
    /**
     * * Gerente de entidade Doctrine.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Renderizador de template PHP.
     * @var type
     */
    private $viewRenderer;

    /**
     * Configuração do aplicativo.
     * @var type
     */
    private $config;

    /**
     * Constrói o serviço.
     */
    public function __construct($entityManager, $viewRenderer, $config)
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    /**
     * Este método adiciona um novo usuário.
     */
    public function deleteRoom($id)
    {
        if (!isset($id)) {
            throw new \Exception("É Necessário um ID para exclusão da Sala");
        }
        $room = $this->entityManager->getRepository(Rooms::Class)->find($id);

        // Remove it and flush
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }


    /**
     * Este método adiciona um novo usuário.
     */
    public function addRoom($data)
    {
        // Não permite várias salas com o mesmo nome.
        if ($this->checkRoomExists($data['room_name'])) {
            throw new \Exception("Uma  sala com o nome" . $data['room_name'] . " já existe");
        }

        // Cria uma nova entidade de usuário.
        $room = new Rooms();
        $room->setRoomName($data['room_name']);
        $room->setArea($data['area']);
        $room->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $room->setDateCreated($currentDate);
        // Adicione a entidade ao gerenciador de entidades.
        $this->entityManager->persist($room);

        // Aplicar mudanças ao banco de dados.
        $this->entityManager->flush();

        return $room;
    }

    /**
     * Verifica se um usuário ativo com determinado endereço de e-mail já existe no banco de dados.
     */
    public function checkRoomExists($room_name)
    {

        $room = $this->entityManager->getRepository(Rooms::class)->findOneBy(['room_name'=>$room_name]);

        return $room !== null;
    }

    /**
     * Este método atualiza os dados de um usuário existente.
     */
    public function updateRoom($room, $data)
    {
        // Não permite alterar o e-mail do usuário se outro usuário com esse e-mail já existir.
        if ($room->getRoomName != $data['room_name'] && $this->checkroomExists($data['room_name'])) {
            throw new \Exception("Outra sala com o nome " . $data['room_name'] . " já existe");
        }
        // nome da sala não pode ser alterado dentro deste modelo
        $room->setArea($data['area']);
        $room->setStatus($data['status']);

        // Aplica Mudanças no Banco de Dados.
        $this->entityManager->flush();

        return true;
    }


}

