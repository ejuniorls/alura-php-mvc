<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Infra\EntityManagerCreator;

class Persistencia implements InterfaceControladorRequisicao
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */

    public function __construct()
    {
        $this->entityManager = (new EntityManagerCreator())
            ->getEntityManager();
    }

    public function processaRequisicao(): void
    {
        // pegar dados do formulário e filtra
        $descricao = filter_input(
            INPUT_POST,
            'descricao',
            FILTER_SANITIZE_STRING
        );

        // montar modelo curso
        $curso = new Curso();
        $curso->setDescricao($descricao);

        $id = filter_input(
            INPUT_GET,
            'id',
            FILTER_VALIDATE_INT
        );

        if (!is_null($id) && $id !== false) {
            // atualiza o curso
            $curso->setId($id);
            $this->entityManager->merge($curso);
        } else {
            // inserir no banco
            $this->entityManager->persist($curso);
        }

        $this->entityManager->flush();

        header('Location: /listar-cursos', false, 302);
    }
}