<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SubscriptionPlan;
use App\Repository\SubscriptionPlanRepository;
use App\Service\SubscriptionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[asController]
#[Route(path: '/subscriptions', name: 'subscriptions_', defaults: ['_format' => 'html'], methods: ['GET'])]
#[IsGranted('IS_AUTHENTICATED')]
final class SubscriptionController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index(SubscriptionPlanRepository $planRepository): Response
    {
        $plans = $planRepository->fetchAvailablePlans();

        return $this->render(
            'subscriptions/index.html.twig',
            [
                'controller_name' => 'IndexController',
                'plans'           => $plans,
            ],
        );
    }

    #[Route(path: '/{id<\d+>}', name: 'details', methods: ['GET'])]
    public function details(SubscriptionPlan $subscriptionPlan): Response
    {
        return $this->render(
            'subscriptions/details.html.twig',
            [
                'controller_name' => 'IndexController',
                'plan'            => $subscriptionPlan,
            ],
        );
    }

    #[Route(path: '/clear', name: 'clear')]
    public function clear(SubscriptionService $subscriptionService): Response
    {
        $subscriptionService->cancelSubscription();
        $this->addFlash('info', 'Subscription removed');

        return $this->redirectToRoute('app_index');
    }
}
