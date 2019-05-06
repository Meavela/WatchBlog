<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ProfileEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile/you/", name="profile", methods={"GET"})
     */
    public function profile(Security $security)
    {
        $userConnected = $security->getUser();
        $hasRoleAdmin = false;
        $hasRoleSuperAdmin = false;
        foreach ($userConnected->getRoles() as $role){
            if ($role == "ROLE_ADMIN"){
                $hasRoleAdmin = true;
            }
            if ($role == "ROLE_SUPER_ADMIN"){
                $hasRoleSuperAdmin = true;
            }
        }
        return $this->render('user/profile.html.twig', [
            'userConnected' => $userConnected,
            'hasRoleAdmin' => $hasRoleAdmin,
            'hasRoleSuperAdmin' => $hasRoleSuperAdmin,
        ]);
    }

    /**
     * @Route("/profile/you/edit/", name="profile_edit", methods={"GET","POST"})
     */
    public function profileEdit(Request $request, Security $security): Response
    {
        $userConnected = $security->getUser();
        $form = $this->createForm(ProfileEditType::class, $userConnected);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded Image file
            // /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $userConnected->getImageFile();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            // moves the file to the directory where image are stored
            $file->move(
                $this->getParameter('kernel.project_dir') . '/public/blog/uploads/users',
                $fileName
            );

            // updates the 'image' property to store the filename
            $userConnected->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile', [
                'userConnected' => $userConnected,
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'userConnected' => $userConnected,
            'user' => $userConnected,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{id}/", name="user", methods={"GET"})
     */
    public function user(User $user, Security $security): Response
    {
        $userConnected = $security->getUser();
        $hasRoleAdmin = false;
        $hasRoleSuperAdmin = false;
        foreach ($user->getRoles() as $role){
            if ($role == "ROLE_ADMIN"){
                $hasRoleAdmin = true;
            }
            if ($role == "ROLE_SUPER_ADMIN"){
                $hasRoleSuperAdmin = true;
            }
        }

        return $this->render('user/user.html.twig', [
            'userConnected' => $userConnected,
            'user' => $user,
            'hasRoleAdmin' => $hasRoleAdmin,
            'hasRoleSuperAdmin' => $hasRoleSuperAdmin,
        ]);
    }
    /**
     * @Route("/profile/{id}/toAdmin", name="user_toAdmin", methods={"GET","POST"})
     */
    public function userToAdmin(User $user, Security $security): Response
    {
        $userConnected = $security->getUser();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        $isSuperAdmin = false;
        foreach ($users as $User)
        {
            if ($User == $userConnected){
                $currentUser = $User;
            }
        }
        foreach ($currentUser->getRoles() as $role)
        {
            if ($role == "ROLE_SUPER_ADMIN"){
                $isSuperAdmin = true;
            }
        }

        if ($isSuperAdmin){
            $user->setAdmin(true);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('user/user.html.twig', [
                'userConnected' => $userConnected,
                'user' => $user,
                'hasRoleSuperAdmin' => true,
            ]);

        }else{
            return $this->render('user/user.html.twig', [
                'userConnected' => $userConnected,
                'user' => $user,
                'hasRoleSuperAdmin' => false,
            ]);
        }

    }

    /**
     * @Route("/profile/{id}/toUser", name="admin_toUser", methods={"GET","POST"})
     */
    public function adminToUser(User $user, Security $security): Response
    {
        $userConnected = $security->getUser();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $currentUser = new User();
        $currentUserIsSuperAdmin = false;
        $userIsSuperAdmin = false;
        foreach ($users as $User)
        {
            if ($User == $userConnected){
                $currentUser = $User;
            }
        }
        foreach ($currentUser->getRoles() as $role)
        {
            if ($role == "ROLE_SUPER_ADMIN"){
                $currentUserIsSuperAdmin = true;
            }
        }

        foreach ($user->getRoles() as $role){
            if ($role == "ROLE_SUPER_ADMIN"){
                $userIsSuperAdmin = true;
            }
        }

        if ($currentUserIsSuperAdmin && !$userIsSuperAdmin){
            $user->setAdmin(false);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('user/user.html.twig', [
                'userConnected' => $userConnected,
                'user' => $user,
                'hasRoleAdmin' => false,
                'hasRoleSuperAdmin' => $userIsSuperAdmin,
            ]);
        }else{
            return $this->render('user/user.html.twig', [
                'userConnected' => $userConnected,
                'user' => $user,
                'hasRoleAdmin' => true,
                'hasRoleSuperAdmin' => $userIsSuperAdmin,
            ]);
        }

    }
}