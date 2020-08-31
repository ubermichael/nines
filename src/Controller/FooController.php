<?php

namespace App\Controller;

use App\Entity\Foo;
use App\Form\FooType;
use App\Repository\FooRepository;

use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;
use Nines\UtilBundle\Controller\PaginatorTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/foo")
 * @IsGranted("ROLE_USER")
 */
class FooController extends AbstractController implements PaginatorAwareInterface
{
    use PaginatorTrait;

    /**
     * @Route("/", name="foo_index", methods={"GET"})
     * @param Request $request
     * @param FooRepository $fooRepository
     *
     * @Template()
     *
     * @return array
     */
    public function index(Request $request, FooRepository $fooRepository) : array
    {
        $query = $fooRepository->indexQuery();
        $pageSize = $this->getParameter('page_size');
        $page = $request->query->getint('page', 1);

        return [
            'foos' => $this->paginator->paginate($query, $page, $pageSize),
        ];
    }

    /**
     * @Route("/search", name="foo_search", methods={"GET"})
     *
     * @Template()
     *
     * @return array
     */
    public function search(Request $request, FooRepository $fooRepository) {
        $q = $request->query->get('q');
        if ($q) {
            $query = $fooRepository->searchQuery($q);
            $foos = $this->paginator->paginate($query, $request->query->getInt('page', 1), $this->getParameter('page_size'), array('wrap-queries'=>true));
        } else {
            $foos = [];
        }

        return [
            'foos' => $foos,
            'q' => $q,
        ];
    }

    /**
     * @Route("/typeahead", name="foo_typeahead", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function typeahead(Request $request, FooRepository $fooRepository) {
        $q = $request->query->get('q');
        if ( ! $q) {
            return new JsonResponse([]);
        }
        $data = [];
        foreach ($fooRepository->typeaheadSearch($q) as $result) {
            $data[] = [
                'id' => $result->getId(),
                'text' => (string)$result,
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/new", name="foo_new", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function new(Request $request) {
        $foo = new Foo();
        $form = $this->createForm(FooType::class, $foo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($foo);
            $entityManager->flush();
            $this->addFlash('success', 'The new foo has been saved.');

            return $this->redirectToRoute('foo_show', ['id' => $foo->getId()]);
        }

        return [
            'foo' => $foo,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/new_popup", name="foo_new_popup", methods={"GET","POST"})
     * @Template()
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function new_popup(Request $request) {
        return $this->new($request);
    }

    /**
     * @Route("/{id}", name="foo_show", methods={"GET"})
     * @Template()
     * @param Foo $foo
     *
     * @return array
     */
    public function show(Foo $foo) {
        return [
            'foo' => $foo,
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}/edit", name="foo_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Foo $foo
     *
     * @Template()
     *
     * @return array|RedirectResponse
     */
    public function edit(Request $request, Foo $foo) {
        $form = $this->createForm(FooType::class, $foo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'The updated foo has been saved.');

            return $this->redirectToRoute('foo_show', ['id' => $foo->getId()]);
        }

        return [
            'foo' => $foo,
            'form' => $form->createView()
        ];
    }

    /**
     * @IsGranted("ROLE_CONTENT_ADMIN")
     * @Route("/{id}", name="foo_delete", methods={"DELETE"})
     * @param Request $request
     * @param Foo $foo
     *
     * @return RedirectResponse
     */
    public function delete(Request $request, Foo $foo) {
        if ($this->isCsrfTokenValid('delete' . $foo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($foo);
            $entityManager->flush();
            $this->addFlash('success', 'The foo has been deleted.');
        }

        return $this->redirectToRoute('foo_index');
    }
}
