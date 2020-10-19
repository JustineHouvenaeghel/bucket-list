<?php

namespace App\Controller;

use App\Entity\Idea;
use App\Entity\IdeaUser;
use App\Form\IdeaType;
use App\Repository\CategoryRepository;
use App\Repository\IdeaRepository;
use App\Repository\IdeaUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/idea")
 */
class IdeaController extends AbstractController
{
    /**
     * @Route("", name="idea_list")
     */
    public function list(CategoryRepository $categoryRepository, IdeaRepository $ideaRepository, PaginatorInterface $paginator, Request $request)
    {
        $categories = $categoryRepository->findAll();

        $filter = $request->query->get('filter');

        $ideas = $paginator->paginate(
            $ideaRepository->findListIdeasWithCategories($filter),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('idea/list.html.twig', [
            "ideas" => $ideas,
            "categories" => $categories,
        ]);
    }

    /**
     * @Route("/{id}", name="idea_detail", requirements={"id": "\d+"})
     */
    public function detail($id, Request $request, IdeaRepository $ideaRepository, IdeaUserRepository $ideaUserRepository)
    {
        $idea = $ideaRepository->find($id);
        $user = $this->getUser();

        $userIdea = $ideaUserRepository->findOneBy(array('user' => $user, 'idea' => $idea));

        return $this->render('idea/detail.html.twig', [
            "idea" => $idea,
            "userIdea" => $userIdea,
        ]);
    }

    /**
     * @Route("/add", name="idea_add")
     */
    public function add(EntityManagerInterface $em, Request $request)
    {
        $idea = new Idea();
        $user = $this->getUser();

        $idea->setAuthor($user->getUsername());

        $ideaForm = $this->createForm(IdeaType::class, $idea);

        $ideaForm->handleRequest($request);

        if($ideaForm->isSubmitted() && $ideaForm->isValid()) {
            $idea->setIsPublished(true);
            $idea->setDateCreated(new \DateTime());
            $em->persist($idea);
            $em->flush();

            $this->addFlash('success', 'Idea successfully added!');
            return $this->redirectToRoute('idea_detail', ['id' => $idea->getId()]);
        }

        return $this->render('idea/add.html.twig', [
            'ideaForm' => $ideaForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="idea_edit", requirements={"id": "\d+"})
     */
    public function edit(EntityManagerInterface $em, Request $request, Idea $idea)
    {
        /*$idea = new Idea();
        $user = $this->getUser();

        $idea->setAuthor($user->getUsername());*/

        $ideaForm = $this->createForm(IdeaType::class, $idea);

        $ideaForm->handleRequest($request);

        if($ideaForm->isSubmitted() && $ideaForm->isValid()) {
            $idea->setIsPublished(true);
            $idea->setDateCreated(new \DateTime());
            $em->persist($idea);
            $em->flush();

            $this->addFlash('success', 'Idea successfully edited!');
            return $this->redirectToRoute('idea_detail', ['id' => $idea->getId()]);
        }

        return $this->render('idea/edit.html.twig', [
            'ideaForm' => $ideaForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="idea_delete", requirements={"id": "\d+"})
     */
    public function delete(EntityManagerInterface $em, Request $request, Idea $idea)
    {
        $submittedToken = $request->request->get('token');

        // 'delete-item' is the same value used in the template to generate the token
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $em->remove($idea);
            $em->flush();

            $this->addFlash('success', 'Idea successfully deleted');
            return $this->redirectToRoute('idea_list');
        }
    }

    /**
     * @Route("/mylist", name="idea_my_list")
     */
    public function myList( IdeaUserRepository $ideaRepository, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getUser();

        $ideas = $paginator->paginate(
            $ideaRepository->findIdeasInMyList($user),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('idea/mylist.html.twig', [
            "userIdeas" => $ideas,
        ]);
    }

    /**
     * @Route("/{id}/addtolist", name="add_idea_to_my_list", requirements={"id": "\d+"})
     */
    public function addToMyList(EntityManagerInterface $em, Idea $idea)
    {
        $userIdea = new IdeaUser();
        $user = $this->getUser();
        $userIdea->setUser($user);
        $userIdea->setIdea($idea);
        $userIdea->setDateAdded(new \DateTime());

        $em->persist($userIdea);
        $em->flush();

        $this->addFlash('success', 'Idea successfully added to your list');
        return $this->redirectToRoute('idea_detail', ['id' => $idea->getId()]);

    }
}
