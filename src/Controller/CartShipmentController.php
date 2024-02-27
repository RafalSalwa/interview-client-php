<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Service\CartCalculator;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/cart/shipment', name: 'shipment_', methods: ['GET', 'POST'])]
final class CartShipmentController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function shipment(
        Request $request,
        CartService $cartService,
        CartCalculator $cartCalculator,
        AddressRepository $repository,
    ): Response {
        $user = $this->getUser();

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $user->addDeliveryAddress($address);
            $address->setUser($user);
            $repository->save($address);
        }

        return $this->render(
            'shipping/step_one.html.twig',
            [
                'form' => $form->createView(),
                'deliveryAddresses' => $user->getDeliveryAddresses(),
                'cart' => $cartService->getCurrentCart(),
                'payment' => $cartCalculator->calculatePayment($cartService->getCurrentCart()),
            ],
        );
    }
}