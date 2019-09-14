<?php
/**
 * CategoryController
 * @author Aneta Satława
 * @package App\Controller
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 *
 * @package App\Controller
 *
 * @Route("/admrights/category")
 */
class CategoryController extends AbstractController
{
    /**
     * Index CategoryMenu Action.
     *
     * @param Request $request
     * @param CategoryRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * @Route(
     *     "/",
     *     name="category_index",
     * )
     */
    public function index(Request $request, CategoryRepository $repository, PaginatorInterface $paginator): Response
    {
        $categorypag = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Category::NUMBER_OF_ITEMS
        );
        return $this->render(
            'category/index.html.twig',
            ['categorypag' => $categorypag]
        );
    }

    /**
     * New Action Category.
     *
     * @param Request $request
     * @param CategoryRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="category_new",
     * )
     */
    public function new(Request $request, CategoryRepository $repository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);

            $this->addFlash('success', 'Kategoria została dodana');

            return $this->redirectToRoute('category_new');
        }

        return $this->render(
            'category/newcategory.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit Action Category.
     *
     * @param Request $request
     * @param Category $category
     * @param CategoryRepository $repository
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="category_edit",
     * )
     */
    public function edit(Request $request, Category $category, CategoryRepository $repository): Response
    {
        $form = $this->createForm(CategoryType::class, $category, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($category);

            $this->addFlash('success', 'Edycja kategorii przebiegła poprawnie!');

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/editcategory.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }


    /**
     *
     * Delete Action Category.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/{id}/delete",
     *     name="category_delete",
     *     requirements={"id": "[1-9]\d*"}
     *   )
     */
    public function deleteCategory($id)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();

        if ($category->getArticle()->count() == 0) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Kategoria została usunięta!');
            return $this->redirectToRoute("category_index");
        } else {
            $this->addFlash('danger', 'Kategoria jest jeszcze używana. Można usuwać tylko puste kategorie!');
            return $this->redirectToRoute("category_index");
        }
    }
}
