<?php
/**
 * ArticleAdminController
 * @author Aneta Satława
 * @package App\Controller
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ArticleAdminController
 * @Route("admrights")
 */
class ArticleAdminController extends AbstractController
{
    /**
     * Stronaadm page with paginator.
     *
     * @param Request $request
     * @param ArticleRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     * @Route("/", name="app_admini")
     */
    public function stronaadm(Request $request, ArticleRepository $repository, PaginatorInterface $paginator): Response
    {
        $pags = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Article::NUMBER_OF_ITEMS
        );

        return $this->render('admrights/admini.html.twig', [
            'pagination' => $pags,
        ]);
    }

    /**
     * New Article Action.
     *
     * @param Request $request
     * @param ArticleRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/article/new",
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
            $article->setSlug('title' . rand(100, 900));
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
     * Edit Article Action.
     *
     * @param Request $request
     * @param Article $article
     * @param ArticleRepository $repository
     * @return Response
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
     * Delete Article Action.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/delete",
     *     name="article_delete",
     *     requirements={"id": "[1-9]\d*"}
     *   )
     */
    public function deleteArticle($id)
    {
        $comment = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', 'Artykuł został usunięty!');
        return $this->redirectToRoute("app_admini");
    }


    /**
     * View AboutProject Action.
     * @param ArticleRepository $repository
     * @return Response
     *
     * @Route(
     *     "/ostronie",
     *     name="o_stronie",
     * )
     */
    public function view(ArticleRepository $repository): Response
    {
        return $this->render(
            'info/ostronie.html.twig',
            ['repo' => $repository]
        );
    }
}
