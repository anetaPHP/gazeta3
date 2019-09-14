<?php
/**
 * ArticleController.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleController.
 */
class ArticleController extends AbstractController
{
    /**
     * Action Homepage Articlelist.
     *
     * @param Request            $request
     * @param ArticleRepository  $repository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @Route("/", name="app_stronastartowa")
     */
    public function stronastartowa(
        Request $request,
        PaginatorInterface $paginator,
        ArticleRepository $repository
    ): Response {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Article::NUMBER_OF_ITEMS
        );

        return $this->render('article/home.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * View Action Article as User with Paginator.
     *
     * @param Article            $article
     * @param Request            $request
     * @param CommentRepository  $repository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/article/{id}",
     *     name="article_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(
        Article $article,
        Request $request,
        CommentRepository $repository,
        PaginatorInterface $paginator
    ): Response {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        //$comments = $repository->queryForArticle($article)->getQuery()->getResult();

        $comments = $paginator->paginate(
            $repository->queryForArticle($article),
            $request->query->getInt('page', 1),
            Comment::NUMBER_OF_ITEMS
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setArticle($article);

            $repository->save($comment);

            $this->addFlash('success', 'Komentarz został dodany');

            return $this->redirectToRoute('app_stronastartowa');
        }

        return $this->render(
            'article/show.html.twig',
            [
                'article' => $article,
                'comments' => $comments,
                'form' => $form->createView(), ]
        );
    }
}
