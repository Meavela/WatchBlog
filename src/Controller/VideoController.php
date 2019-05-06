<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\TagVideo;
use App\Entity\TypeVideo;
use App\Entity\User;
use App\Entity\Video;
use App\Form\VideoType;
use App\Form\VideoNotationType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;


class VideoController extends AbstractController
{
    /**
     * @Route("/video/new/", name="video_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security)
    {
        $userConnected = $security->getUser();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        foreach ($users as $user)
        {
            if ($user == $userConnected){
                $currentUser = $user;
            }
        }
        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded Image file
            // /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $video->getImageFile();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // moves the file to the directory where image are stored
            $file->move(
                $this->getParameter('kernel.project_dir') . '/public/blog/uploads/videos',
                $fileName
            );

            // updates the 'image' property to store the filename
            $video->setImage($fileName);
            $video->setAddedBy($currentUser);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('video_list');
        }

        return $this->render('video/new.html.twig', [
            'video' => $video,
            'userConnected' => $userConnected,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/video/list/", name="video_list", methods={"GET"})
     */
    public function index(VideoRepository $videoRepository, Security $security): Response
    {
        $userConnected = $security->getUser();

        return $this->render('video/index.html.twig', [
            'userConnected' => $userConnected,
            'videos' => $videoRepository->findAll(),
        ]);
    }
    /**
     * @Route("/video/{id}/", name="video_show", methods={"GET"})
     */
    public function show(Video $video, Security $security): Response
    {
        $userConnected = $security->getUser();

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        $usersNotation = $video->getUsersAddedNotation();
        $hasVote = false;
        foreach ($users as $user)
        {
            if ($user == $userConnected){
                $currentUser = $user;
            }
            foreach ($usersNotation as $userVote){
                if ($user == $userVote){
                    $hasVote = true;
                }
            }
        }

        $types = $this->getDoctrine()->getRepository(TypeVideo::class)->findAll();
        $tags = $this->getDoctrine()->getRepository(TagVideo::class)->findAll();
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('video/show.html.twig', [
            'userConnected' => $currentUser,
            'hasVote' => $hasVote,
            'users' => $users,
            'types' => $types,
            'comments' => $comments,
            'tags' => $tags,
            'video' => $video,
        ]);
    }

    /**
     * @Route("/video/{id}/delete", name="video_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Video $video, Security $security): Response
    {
        $userConnected = $security->getUser();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        $isAdmin = false;
        foreach ($users as $user)
        {
            if ($user == $userConnected){
                $currentUser = $user;
            }
        }
        foreach ($currentUser->getRoles() as $role)
        {
            if ($role == "ROLE_ADMIN"){
                $isAdmin = true;
            }
        }


        if ($currentUser->getId() == $video->getAddedBy()->getId() || $isAdmin) {
            if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($video);
                $entityManager->flush();
            }
        }else{
            return $this->redirectToRoute('video_show', [
                'id' => $video->getId(),
            ]);
        }
        return $this->redirectToRoute('video_list');
    }

    /**
     * @Route("video/{id}/notation", name="video_notation", methods={"GET","POST"})
     */
    public function notation(Request $request, Video $video, Security $security): Response
    {
        $userConnected = $security->getUser();
        $lastNotation = $video->getNotation();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        $usersNotation = $video->getUsersAddedNotation();
        $hasVote = false;
        foreach ($users as $user)
        {
            if ($user == $userConnected){
                $currentUser = $user;
            }
            foreach ($usersNotation as $userVote){
                if ($user == $userVote){
                    $hasVote = true;
                }
            }
        }

        if ($userConnected != null && $hasVote == false){

            $form = $this->createForm(VideoNotationType::class, $video);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $newNotation = $video->getNotation();

                $video->setNotation(($lastNotation+$newNotation)/2);
                $video->addUsersAddedNotation($currentUser);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('video_show', [
                    'id' => $video->getId(),
                ]);
            }

            return $this->render('video/notation.html.twig', [
                'userConnected' => $currentUser,
                'video' => $video,
                'form' => $form->createView(),
            ]);
        }else{
            return $this->redirectToRoute('video_show', [
                'id' => $video->getId(),
            ]);
        }
    }

    /**
     * @Route("/video/{id}/edit", name="video_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Video $video, Security $security): Response
    {
        $userConnected = $security->getUser();
        $lastNotation = $video->getNotation();

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        $userAddedVideo = $video->getAddedBy();
        $hasAdd = false;
        foreach ($users as $user)
        {
            if ($user == $userConnected){
                $currentUser = $user;
            }
        }

        if ($currentUser == $userAddedVideo){
            $hasAdd = true;
        }
        $roles = $currentUser->getRoles();
        foreach ($roles as $role)
        {
            if ($role == "ROLE_ADMIN"){
                $hasAdd = true;
            }
        }

        if ($userConnected != null && $hasAdd == true){
            $form = $this->createForm(VideoType::class, $video);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // $file stores the uploaded Image file
                // /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $video->getImageFile();

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                // moves the file to the directory where image are stored
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/blog/uploads/videos',
                    $fileName
                );

                // updates the 'image' property to store the filename
                $newNotation = $video->getNotation();

                $video->setNotation(($lastNotation+$newNotation)/2);
                $video->setImage($fileName);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('video_show', [
                    'id' => $video->getId(),
                ]);
            }

            return $this->render('video/edit.html.twig', [
                'userConnected' => $userConnected,
                'video' => $video,
                'form' => $form->createView(),
            ]);
        }else{
            return $this->redirectToRoute('video_show', [
                'id' => $video->getId(),
            ]);        }

    }

}