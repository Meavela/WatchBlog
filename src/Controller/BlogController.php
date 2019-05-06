<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/watchblog/", name="watchblog", methods={"GET"})
     */
    public function blog(Security $security)
    {
        $userConnected = $security->getUser();
        return $this->render('blog.html.twig', [
            'userConnected' => $userConnected,
        ]);
    }
}