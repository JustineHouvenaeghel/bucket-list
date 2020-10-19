<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Idea;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/idea/{id}/comment", name="add_comment", requirements={"id": "\d+"})
     */
    public function add(EntityManagerInterface $em, Request $request, Idea $idea)
    {
        $comment = new Comment();
        $user = $this->getUser();

        $comment->setAuthor($user->getUsername());
        $comment->setIdea($idea);

        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($user);
            $comment->setDateCreated(new \DateTime());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Your comment was successfully added!');
            return $this->redirectToRoute('idea_detail', ['id' => $idea->getId()]);
        }

        return $this->render('comment/add.html.twig', [
            'commentForm' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/idea/{id}/{comment}/delete", name="delete_comment", requirements={"id": "\d+", "comment": "\d+"})
     */
    public function delete(EntityManagerInterface $em, Idea $idea, Comment $comment)
    {

        $em->remove($comment);
        $em->flush();

        $this->addFlash('success', 'Comment successfully deleted');
        return $this->redirectToRoute('idea_detail', ['id' => $idea->getId()]);
    }
}
