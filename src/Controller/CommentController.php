<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Video;
use App\Form\CommentType;
use DateTime as DateTimeAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentController extends AbstractController
{
    /**
     * @Route("video/{id}/comment/new/", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security)
    {
        $userConnected = $security->getUser();

        $comment = new Comment();
        $currentUrl = $request->getUri();

        $id = explode("/", $currentUrl)[4];

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($userConnected != null){
                $comment->setCreatedBy($userConnected);
            }
            else
            {
                $anonyme = $this->getDoctrine()->getRepository(User::class)->find(0);
                $comment->setCreatedBy($anonyme);
            }
            $comment->setCreatedAt(new DateTimeAlias());

            $videos = $this->getDoctrine()->getRepository(Video::class)->findAll();


            foreach ($videos as $video){
                if ($video->getId() == $id){
                    $comment->setCreatedFor($video);
                }
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('video_show', array('id' => $id));
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'currentUrl' => $currentUrl,
            'id' => $id,
            'userConnected' => $userConnected,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("video/{id}/comment/delete/{comment}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        $currentUrl = $request->getUri();

        $id = explode("/", $currentUrl)[4];
        return $this->redirectToRoute('video_show', array('id' => $id));
    }
}