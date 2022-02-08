<?php

namespace App\Controller;

use App\Entity\Kontrahent;
use App\Form\KontrahentType;
use App\Repository\KontrahentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/kontrahent")
 */
class KontrahentController extends AbstractController
{
    /**
     * @Route("/", name="kontrahent_index", methods={"GET", "POST"})
     */
    public function index(KontrahentRepository $kontrahentRepository, Request $request): Response
    {
        $data = $request->get('search');
        $repository = $this->getDoctrine()->getRepository(Kontrahent::class);
        $kontrahents = [];
        if($data){
             $kontrahents = $repository->findByNameField($data);
        }   
        return $this->render('kontrahent/index.html.twig', [
            'kontrahents' => $kontrahents,
        ]);
    }
    
    /**
     * @Route("/update", name="kontrahenci_update", methods={"GET"})
     */
    
    public function update(KontrahentRepository $kontrahentRepository): Response {
        $repository = $this->getDoctrine()->getRepository(Kontrahent::class);
        $entityManager = $this->getDoctrine()->getManager();
        $finder = new Finder();
        $finder->files()->in(__DIR__)->name('kontrahenci.csv');
        set_time_limit(1200);
        if ($finder->hasResults()) {
            $kontrahents = $repository->findAll();
            foreach ($kontrahents as $kontrahent) {
                    
                    $entityManager->remove($kontrahent);
                    
                }
                $entityManager->flush();
        }else{
            echo ('brak pliku importu, skontaktuj się z Adamem');
        }
        $objDateTime = new DateTime('NOW');
// decoding CSV contents
        foreach ($finder as $file) {
            $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
            // instantiation, when using it inside the Symfony framework
            $contents = $file->getContents();
            // decoding CSV contents
            $kontrahent_csv = $serializer->decode($contents, 'csv');
           //var_dump($products_csv);
            foreach ($kontrahent_csv as $key => $value) {
                $kontrahent = new Kontrahent();
                $kontrahent->setName($value['Nazwa kontrahenta']);
                $kontrahent->setAdress($value['Miasto']);
                $kontrahent->setPhone($value['Nr telefonu']);
                $kontrahent->setEmail($value['E-mail kontrahenta']);
                $kontrahent->setNip($value['NIP']);
                $kontrahent->setNotices($value['Uwagi']);
                $kontrahent->setPostCode($value['Kod']);
                $kontrahent->setStreet($value['Ulica, nr lokalu']);
                $kontrahent->setClassName($value['Nazwa klasyfikacji']);
                $kontrahent->setGroupName($value['Nazwa grupy']);
                $entityManager->persist($kontrahent);

 
        }
        $entityManager->flush();
        }
        return $this->render('kontrahent/index.html.twig', [
        'kontrahents' => $kontrahentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="kontrahent_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $kontrahent = new Kontrahent();
        $form = $this->createForm(KontrahentType::class, $kontrahent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($kontrahent);
            $entityManager->flush();

            return $this->redirectToRoute('kontrahent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kontrahent/new.html.twig', [
            'kontrahent' => $kontrahent,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="kontrahent_show", methods={"GET"})
     */
    public function show(Kontrahent $kontrahent): Response
    {
        return $this->render('kontrahent/show.html.twig', [
            'kontrahent' => $kontrahent,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="kontrahent_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Kontrahent $kontrahent): Response
    {
        $form = $this->createForm(KontrahentType::class, $kontrahent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('kontrahent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kontrahent/edit.html.twig', [
            'kontrahent' => $kontrahent,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="kontrahent_delete", methods={"POST"})
     */
    public function delete(Request $request, Kontrahent $kontrahent): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kontrahent->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($kontrahent);
            $entityManager->flush();
        }

        return $this->redirectToRoute('kontrahent_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/set/{id}", name="pick_kontrahent", methods={"GET"})
     */
    public function pick(Kontrahent $kontrahent): Response
    {
        $session = new Session();
        $session->start();

        // set and get session attributes
        $session->set('kontrahent', $kontrahent);
        $session->getFlashBag()->add('notice', 'Kontrahent wybrany');
        
        return $this->redirectToRoute('kontrahent_index', [], Response::HTTP_SEE_OTHER);
       
    }
    
    /**
     * @Route("/search", name="kontrahent_search")
     */
     public function searchAction(Request $request){
            
         
             $searchTerm = $request->get('q');  
             $em = $this->getDoctrine()->getManager();
             $results = $em->getRepository(Kontrahent::class)->findByNameField($searchTerm);
             //$results = $query->getResult();
 
             if(!$results) {
            $result['entities']['error'] = "brak kontrahenta";
        } else {
            $result['entities'] = $this->getRealEntities($results);
        }

        return new Response(json_encode($result));

             }
             
             public function getRealEntities($entities){

            foreach ($entities as $entity){
                $realEntities[$entity->getId()] = $entity->getName();
            }

      return $realEntities;
  }
}
