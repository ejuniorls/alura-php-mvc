<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Persistencia implements RequestHandlerInterface
{
    use FlashMessageTrait;

    /** @var EntityManagerInterface */

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // pegar dados do formulário e filtra
        $descricao = filter_var(
            $request->getParsedBody()['descricao'],
            FILTER_SANITIZE_STRING
        );

        // montar modelo curso
        $curso = new Curso();
        $curso->setDescricao($descricao);

        $id = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );

        $tipo_mensagem = 'success';
        if (!is_null($id) && $id !== false) {
            // atualiza o curso
            $curso->setId($id);
            $this->entityManager->merge($curso);
            $this->defineMensagem($tipo_mensagem, 'Curso atualizado: ' . $curso->getDescricao());
        } else {
            // inserir no banco
            $this->entityManager->persist($curso);
            $this->defineMensagem($tipo_mensagem, 'Curso inserido: ' . $curso->getDescricao());
        }

        $this->entityManager->flush();

        return new Response(302, ['Location' => '/listar-cursos']);
    }
}