<?php

namespace App\Controller;

use App\Form\CurrencyExchangeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class CurrencyExchangeController extends AbstractController
{
    /**
     * @Route("/instagram/users", name="app_instagram_index")
     */
    public function index(): Response
    {
        return $this->render('instagram_user_parser/index.html.twig', [
            'users' => $instagramUserRepository->findAll(),
        ]);
    }


    /**
     * @Route("/instagram/new", name="app_instagram_new")
     */
    public function parseInstagramUser
    (
        Request                 $request,
        ParameterBagInterface   $parameterBag,
    ): Response
    {
        $form = null;
        if ($request->isMethod('POST')) {
            $form = $this->createForm(CurrencyExchangeType::class);

            $form->handleRequest($request);
        }

        if ($form && $form->isSubmitted() && $form->isValid()) {

            $instagramUserName = $form->getData()->getUsername();
            $instagramUserInDatabase = $instagramUserRepository->findOne
            (
                'username',
                    $instagramUserName,
                '='
            )[0] ?? null;

            // if user exists redirect to table with data by id
            if (!is_null($instagramUserInDatabase)) {
                return $this->render('instagram_user_parser/index.html.twig', [
                    'users' => [$instagramUserInDatabase],
                ]);
            }


            // do stuff
            // do stuff
            // do stuff
            // do stuff


            // failed to parse
            if ($instagramUser === false) {

                // display error
                return $this->renderForm('instagram_user_parser/new.html.twig', [
                    'form' => $form,
                    'failed_to_parse' => true
                ]);
            }

            // display stufff
            // display stufff
            // display stufff
            // display stufff




            return $this->render('instagram_user_parser/index.html.twig', [
                'users' => [$instagramUser],
            ]);
        }

        return $this->renderForm('instagram_user_parser/new.html.twig', [
            'form' => $form,
            'failed_to_parse' => false
        ]);
    }
}
