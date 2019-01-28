<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProductController extends Controller
{

    public function getProductnameAction($product_id)
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')

            ->findOneBy(array('idproduct' => $product_id));

        return $this->render(
            'AppBundle:Product:productname.html.twig', array(
                'productname' => $product //$productname
            ));

    }
}

