<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Gateway\Sagepay\Deferred;
use Modules\Payment\Gateway\Sagepay\PaymentGateway;
use Modules\Payment\Gateway\Sagepay\Payment;
use \GuzzleHttp\Psr7\Response;
use Modules\Payment\Gateway\Sagepay\Refund;
use Modules\Payment\Gateway\Sagepay\Repeat;
use Modules\Payment\Gateway\Sagepay\VoidPayment;
use Psr\Http\Message\ResponseInterface;


class PaymentController extends Controller
{

    protected $token;
    protected $merchantSessionKey;
    protected $cardIdentifier;

    /**
     * Display a listing of the resource.
     * @return Response
     * @throws \Exception
     */
    public function index(Request $request)
    {

//        $msk = new PaymentGateway();
        $msk = new \Modules\Payment\Gateway\Sagepay\Payment($request);
//        $msk = new \Modules\Payment\Gateway\Sagepay\Repeat($request);
//        $msk = new \Modules\Payment\Gateway\Sagepay\Refund($request);
//        $msk = new \Modules\Payment\Gateway\Sagepay\Deferred($request);
//        $msk = new \Modules\Payment\Gateway\Sagepay\VoidPayment($request);


//        dump($msk->paymentOrder(true));
        dump($msk->paymentOrder(false));
//        dump($msk->repeatOrder(false));
//        dump($msk->refundOrder());
//        dump($msk->deferredOrder(false));
//        dump($msk->voidOrder());

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function payment(Request $request)
    {

        $payment = new Payment($request);

        $payment = $payment->paymentOrder(false);

        if ($payment instanceof ResponseInterface) {

            return json_decode($payment->getBody()->__toString());

        }

        return new JsonResponse($payment, 200);

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function repeat(Request $request)
    {

        $payment = new Repeat($request);

        $payment = $payment->repeatOrder(false);

        if ($payment instanceof ResponseInterface) {

            return json_decode($payment->getBody()->__toString());

        }

        return new JsonResponse($payment, 200);

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function refund(Request $request)
    {

        $payment = new Refund($request);

        $payment = $payment->refundOrder(false);

        if ($payment instanceof ResponseInterface) {

            return json_decode($payment->getBody()->__toString());

        }

        return new JsonResponse($payment, 200);

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function void(Request $request)
    {

        $payment = new VoidPayment($request);

        $payment = $payment->voidOrder(false);

        if ($payment instanceof ResponseInterface) {

            return json_decode($payment->getBody()->__toString());

        }

        return new JsonResponse($payment, 200);

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function defer(Request $request)
    {

        $payment = new Deferred($request);

        $payment = $payment->deferredOrder(false);

        if ($payment instanceof ResponseInterface) {

            return json_decode($payment->getBody()->__toString());

        }

        return new JsonResponse($payment, 200);

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function sessionToken(Request $request)
    {

        $card = new PaymentGateway($request);

        $token = $card->getToken();

        if ( $token instanceof ResponseInterface) {
            return new JsonResponse(json_decode($token->getBody()->__toString()), 200);
        }

        return new JsonResponse(
            'Error creating a Card Token',
            201
        );
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     * @throws \Exception
     */
    public function cardAuthorization(Request $request)
    {

        $card = new PaymentGateway($request);

        $token = $card->createCardIdentifier();

        if ( $token instanceof ResponseInterface) {
            return new JsonResponse(json_decode($token->getBody()->__toString()), 200);
        }

        return new JsonResponse(
            'Error creating a Card Token',
            201
        );
    }

}
