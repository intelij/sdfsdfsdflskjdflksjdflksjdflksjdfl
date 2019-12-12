<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Gateway\Sagepay\PaymentGateway;
use Modules\Payment\Gateway\Sagepay\Payment;
use \GuzzleHttp\Psr7\Response;
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

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('payment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
