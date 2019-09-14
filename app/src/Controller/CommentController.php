<?php
/**
 * CommentController.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentController.
 */
class CommentController extends AbstractController
{
    /**
     * View Action Comment from Adminsite with paginator.
     *
     * @param Article            $article
     * @param Request            $request
     * @param CommentRepository  $repository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     *
     * @Route(
     *     "/articleview/{id}",
     *     name="articleview_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(
        Article $article,
        Request $request,
        CommentRepository $repository,
        PaginatorInterface $paginator
    ): Response {
        $commentspag = $paginator->paginate(
            $repository->queryForArticle($article),
            $request->query->getInt('page', 1),
            Comment::NUMBER_OF_ITEMS
        );

        return $this->render(
            'comment/comment.html.twig',
            [
                'commentspag' => $commentspag,
                'article' => $article, ]
        );
    }

    /**
     * Delete Action Comment.
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/admrights/articleview/{id}/delete",
     *     name="comment_delete",
     *     requirements={"id": "[1-9]\d*"}
     *   )
     */
    public function deleteComment($id)
    {
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', 'Komentarz został usunięty!');

        return $this->redirectToRoute('app_admini');
    }
}
