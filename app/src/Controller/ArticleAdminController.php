<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Mapping\Annotation\Slug;
use http\QueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ArticleAdminController extends AbstractController
{

    /**
     * @Route("/admrights", name="app_admini")
     */
    public function stronastartowa(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Article::class);
        $article = $repository->findAllPublishedOrderedByNewest();
        return $this->render('admrights/admini.html.twig',[
            'article'=> $article,
        ]);
    }


    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\ArticleRepository $repository Article repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/admrights/article/new",
     *     methods={"GET", "POST"},
     *     name="article_new",
     * )
     */
    public function new(Request $request, ArticleRepository $repository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $data =$form->getData();
//            $article =new Article();
//            $article->setTitle($data);
//            $article->setSubtitle($data);
            $article->setCreatedAt(new \DateTime());
            $article->setSlug('title'.rand(100,900));
            $article->setUser($this->getUser());

            $repository->save($article);

            $this->addFlash('success', 'Atykuł został dodany');
            return $this->redirectToRoute('app_admini');
        }

        return $this->render(
            'admrights/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Article                          $article       Article entity
     * @param \App\Repository\ArticleRepository            $repository Article repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="article_edit",
     * )
     */
    public function edit(Request $request, Article $article, ArticleRepository $repository): Response
    {
        $form = $this->createForm(ArticleType::class, $article, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($article);

            $this->addFlash('success', 'Edycja przebiegła poprawnie!');

            return $this->redirectToRoute('app_admini');
        }

        return $this->render(
            'admrights/edit.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Article                     $article   Article entity
     * @param \App\Repository\ArticleRepository        $repository Article repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="article_delete",
     * )
     */
    public function delete(Request $request, Article $article, ArticleRepository $repository): Response
    {
        $form = $this->createForm(ArticleType::class, $article, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($article);
            $this->addFlash('success', 'Wpis został usunięty!');

            return $this->redirectToRoute('app_admini');
        }

        return $this->render(
            'admrights/delete.html.twig',
            [
                'form' => $form->createView(),
                'article' => $article,
            ]
        );
    }

    // ...
    /**
     * View InfoPage
     *
     *
     *
     * @Route(
     *     "/ostronie",
     *     name="o_stronie",
     * )
     */
    public function view(ArticleRepository $repository):Response
    {
        return $this->render(
            'info/ostronie.html.twig',
            ['repo' => $repository]
        );
    }




}
