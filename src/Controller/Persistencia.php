<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;

class Persistencia implements InterfaceControladorRequisicao
{
    use FlashMessageTrait;

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

        $tipo_mensagem = 'success';
        if (!is_null($id) && $id !== false) {
            // atualiza o curso
            $curso->setId($id);
            $this->entityManager->merge($curso);
            $this->defineMensagem($tipo_mensagem, "Curso atualizado: " . $curso->getDescricao());
        } else {
            // inserir no banco
            $this->entityManager->persist($curso);
            $this->defineMensagem($tipo_mensagem, "Curso inserido: " . $curso->getDescricao());
        }

        $this->entityManager->flush();

        header('Location: /listar-cursos', false, 302);
    }
}