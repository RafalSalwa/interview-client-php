<?php

declare(strict_types=1);

namespace App\Controller\GRPC;

use App\Controller\AbstractShopController;
use App\Form\SignInType;
use App\Form\SignUpType;
use App\Form\UserVerifyCodeType;
use App\Model\GRPC\VerificationCodeRequest;
use App\Model\SignInUserInput;
use App\Model\SignUpUserInput;
use App\Protobuf\SignInUserResponse;
use App\Service\GRPC\AuthApiGRPCService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[asController]
#[Route(path: '/grpc', name: 'grpc_')]
final class AuthController extends AbstractShopController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return $this->render('grpc/index.html.twig', []);
    }

    #[Route(path: '/user/sign_up', name: 'sign_up')]
    public function signUp(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $signUpUserInput = new SignUpUserInput();
        $form        = $this->createForm(SignUpType::class, $signUpUserInput);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            $authGRPCService->signUpUser($signUpUserInput->getEmail(),$signUpUserInput->getPassword());
        }

        return $this->render(
            'grpc/sign_up.html.twig',
            [
                'form'              => $form->createView(),
                'grpc_responses'    => $authGRPCService->getResponses(),
            ],
        );
    }

    #[Route(path: '/user/verify', name: 'user_verify')]
    public function verifyUser(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;
        $verificationCodeRequest = new VerificationCodeRequest();

        $form = $this->createForm(UserVerifyCodeType::class, $verificationCodeRequest);
        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            [$grpcResponse, $status] = $authGRPCService->verifyUserByCode(
                $verificationCodeRequest->getVerificationCode(),
            );
        }

        return $this->render(
            'grpc/verify.html.twig',
            [
                'form'          => $form->createView(),
                'grpc_responses'    => $authGRPCService->getResponses(),
                'api_user_response' => $authGRPCService->getUserCredentialsFromLastSignUp(),
            ],
        );
    }

    #[Route(path: '/user/sign_in', name: 'sign_in')]
    public function getTokens(Request $request, AuthApiGRPCService $authApiGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;
        $lastSignUp   = $authApiGRPCService->getUserCredentialsFromLastSignUp();

        $signInUserInput = new SignInUserInput();
        $form        = $this->createForm(SignInType::class, $signInUserInput);

        $form->handleRequest($request);
        if (true === $form->isSubmitted() && true === $form->isValid()) {
            [$grpcResponse, $status] = $authApiGRPCService->signInUser(
                $signInUserInput->getEmail(),
                $signInUserInput->getPassword(),
            );

            if (0 === $status->code && $grpcResponse instanceof SignInUserResponse) {
                $arrUser = $authApiGRPCService->getUserCredentialsFromLastSignUp();

                $arrUser['access_token']  = $grpcResponse->getAccessToken();
                $arrUser['refresh_token'] = $grpcResponse->getRefreshToken();

                $authApiGRPCService->setUserCredentialsFromLastSignUp($arrUser);
            }
        }

        return $this->render(
            'grpc/sign_in.html.twig',
            [
                'form'             => $form->createView(),
                'previous_sign_up_data' => $authApiGRPCService->getUserCredentialsFromLastSignUp(),
                'grpc_status'      => $status,
                'grpc_response'    => $grpcResponse,
                'grpc_credentials' => $lastSignUp,
            ],
        );
    }

    #[Route(path: '/user/details', name: 'user_get')]
    public function getUserDetails(Request $request, AuthApiGRPCService $authGRPCService): Response
    {
        $status       = null;
        $grpcResponse = null;
        $lastSignUp   = $authGRPCService->getUserCredentialsFromLastSignUp();

        $signInUserInput = new SignInUserInput();
        $form        = $this->createForm(SignInType::class, $signInUserInput);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            [$grpcResponse, $status] = $authGRPCService->signInUser(
                $signInUserInput->getUsername(),
                $signInUserInput->getPassword(),
            );

            if (0 === $status->code && $grpcResponse instanceof SignInUserResponse) {
                $arrUser = $authGRPCService->getUserCredentialsFromLastSignUp();

                $arrUser['access_token']  = $grpcResponse->getAccessToken();
                $arrUser['refresh_token'] = $grpcResponse->getRefreshToken();

                $authGRPCService->setUserCredentialsFromLastSignUp($arrUser);
            }
        }

        return $this->render(
            'grpc/sign_in.html.twig',
            [
                'form'             => $form->createView(),
                'grpc_status'      => $status,
                'grpc_response'    => $grpcResponse,
                'grpc_credentials' => $lastSignUp,
            ],
        );
    }
}