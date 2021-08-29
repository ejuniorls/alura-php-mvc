<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Exclusao implements RequestHandlerInterface
{
    use FlashMessageTrait;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $idIdentidade = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );

        $response = new Response(302, ['Location' => '/listar-cursos']);

        if (is_null($idIdentidade) || $idIdentidade === false) {
            $this->defineMensagem('warning', 'Curso não localizado');
            return $response;
        }

        $curso = $this->entityManager->getReference(Curso::class, $idIdentidade);
        $this->entityManager->remove($curso);
        $this->entityManager->flush();
        $this->defineMensagem('success', 'Curso removido');

        return $response;
    }
}