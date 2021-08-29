<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Helper\RenderizadorDeHtmlTrait;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class FormularioEdicao implements RequestHandlerInterface
{
    use RenderizadorDeHtmlTrait, FlashMessageTrait;

    /** @var ObjectRepository */
    private $repositorioDeCursos;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioDeCursos = $entityManager->getRepository(Curso::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = filter_var(
            $request->getQueryParams()['id'],
            FILTER_VALIDATE_INT
        );

        $redirecionaListaCursos = new Response(302, ['Location' => '/listar-cursos']);

        if (is_null($id) || $id === false) {
            $this->defineMensagem('danger', 'ID de curso inválido');
            return $redirecionaListaCursos;
        }

        $curso = $this->repositorioDeCursos->find($id);

        $html = $this->renderizaHtml('cursos/formulario-novo-curso.php', [
            'curso' => $curso,
            'titulo' => 'Alterando curso: ' . $curso->getDescricao()
        ]);

        return new Response(200, [], $html);
    }
}