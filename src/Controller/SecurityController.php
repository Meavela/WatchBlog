<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\VideoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    const ROLE_DEFAULT = 'ROLE_USER';
    /**
     * @Route("/admin")
     */
    public function admin()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    /**
     * @Route("/register", name="register", methods={"GET", "POST"})
     */
    public function register(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);


        $user->setUser(true);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded Image file
            // /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getImageFile();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // moves the file to the directory where image are stored
            $file->move(
                $this->getParameter('kernel.project_dir') . '/public/blog/uploads/users',
                $fileName
            );

            // updates the 'image' property to store the filename
            $user->setImage($fileName);
            $user->setEnabled(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect('/login');
        }

        return $this->render('security/register.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }}