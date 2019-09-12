<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Michelf\MarkdownInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Serializer\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_stronastartowa")
     */
    public function stronastartowa(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        $article = $repository->findAllPublishedOrderedByNewest();
        return $this->render('article/home.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * View action.
     *
     * @param \App\Entity\Article $article Article entity
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\CommentRepository $repository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
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
    public function view(Article $article, Request $request,CommentRepository $repository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//
            $comment->setAuthor($this->getUser());
            $comment->setArticle($article);

            $repository->save($comment);

            $this->addFlash('success', 'Komentarz został dodany');
        }
            return $this->render(
                'article/show.html.twig',
                [
                    'article' => $article,
                    'form' => $form->createView()]
            );
        }



}
