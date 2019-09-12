<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Mapping\Annotation\Slug;
use http\QueryString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{

    /**
     * View action.
     *
     * @param \App\Entity\Article $article Article entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/articleview/{id}",
     *     name="articleview_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Article $article): Response
    {
        return $this->render(
            'comment/comment.html.twig',
            ['article' => $article]
        );
    }


    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Comment                     $comment   Comment entity
     * @param \App\Repository\CommentRepository        $repository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "admrights/articleview/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="comment_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Comment $comment, CommentRepository $repository): Response
    {
      //  $user = $this->getUser();
       // if (!$user || $user != $comment->getAuthor()){
      //  if (!$user || !$user->hasRole("ROLE_ADMIN")){
       //     throw new AccessDeniedHttpException();
       // }
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($comment);
            $this->addFlash('success', 'Komentarz został usunięty!');

            return $this->redirectToRoute('app_admini');
        }

        return $this->render(
            'comment/commentdelete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }


}