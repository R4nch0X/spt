<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sales;
use AppBundle\Repository\SalesRepository;
use AppBundle\Form\Type\SalesType;
use AppBundle\Form\Filter\SalesFilterType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sale controller.
 *
 * @Route("sales")
 */
class SalesController extends Controller
{
    /**
     * Lists all sale entities.
     *
     * @Route("/{pagina}", defaults={"pagina": 1}, requirements={"pagina": "\d+"}, name="sales_index")
     * @Method("GET")
     */
    public function indexAction($pagina, Request $request)
    {

        $filter = $this->get('form.factory')->create(new SalesFilterType());

        $query = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Sales')
                ->createQueryBuilder('r')
                ;
        $product = $request->query->get('sales');


        if ($this->get('request')->query->has($filter->getName())) {
          $filter->submit($this->get('request')->query->get($filter->getName()));
          $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filter, $query);
        }

        $pagination = $this->get('knp_paginator')->paginate(
            $query,
            $pagina,
            $this->container->getParameter("registros_x_pagina"),
            array('defaultSortFieldName' => 'r.date', 'defaultSortDirection' => 'asc')
        );

        return $this->render('sales/index.html.twig', array(
            'sales' => $query,
            'pagination' => $pagination,
            'filter' => $filter->createView()
        ));
    }

    /**
     * Creates a new sale entity.
     *
     * @Route("/new", name="sales_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $sale = new Sales();
        $form = $this->createForm('AppBundle\Form\Type\SalesType', $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sale);
            $em->flush();

            return $this->redirectToRoute('sales_new');
        }

        return $this->render('sales/new.html.twig', array(
            'sale' => $sale,
            'form' => $form->createView(),
        ));
    }

}
