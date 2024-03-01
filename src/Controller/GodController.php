<?php

namespace App\Controller;

use App\Entity\God;
use App\Form\GodType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class GodController extends AbstractController
{
    #[Route('/god', name: 'app_god')]
    public function index(EntityManagerInterface $em, Request $r, SluggerInterface $slugger): Response
    {
        $a_God = new God();
        $form = $this->createForm(GodType::class, $a_God);
        $form->handleRequest($r);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($a_God->getTitle() . '-' . uniqid());
            $a_God->setSlug($slug);
            $a_God->setPraiseCount(0);

            $em->persist($a_God);
            $em->flush();
        }

        $gods = $em->getRepository(God::class)->findAll();

        return $this->render('god/index.html.twig', [
            'gods' => $gods,
            'form' => $form->createView()
        ]);

    }

    #[Route('/god/{slug}', name: 'app_god_show')]
    public function show(God $god, EntityManagerInterface $em, Request $r, SluggerInterface $slugger) 
    {
        
        $form = $this->createForm(GodType::class, $god);
        $form->handleRequest($r);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($god->getTitle() . '-' . uniqid());
            $god->setSlug($slug);

            $em->persist($god);
            $em->flush();

            return $this->redirectToRoute('app_god');
        }

        return $this->render('god/show.html.twig', [
            'edit' => $form->createView(),
            'god' => $god
        ]);
    }

    #[Route('/god/delete/{id}', name: 'app_god_delete')]
    public function delete(EntityManagerInterface $em, God $god, Request $r)
    {
        if (($this->isCsrfTokenValid('delete' . $god->getId(), $r->request->get('csrf')))) {
            $em->remove($god);
            $em->flush();
        }

        return $this->redirectToRoute('app_god');
    }
}
