<?php

namespace Modules\Payment\Http\Controllers;

use Dividebuy\Payment\Contracts\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payment\Gateway\Sagepay\PaymentGateway;
//use Modules\Payment\Gateway\Sagepay\Payment;

class PaymentController extends Controller
{

    protected $token;
    protected $merchantSessionKey;
    protected $cardIdentifier;


    public function __construct()
    {

        $this->payload = json_encode([
            'key' => 'value',
            'merchant' => 'dividebuy-test-account-api',
            'created_at' => date('d-m-Y')
        ]);

        $this->merchantSessionKey = json_encode([
            "expiry" => "2019-12-06T10:43:53.810Z",
            "merchantSessionKey" => "4F84B1EB-C03A-4EA9-94DC-0BF8796C1AB4"
        ]);

        $this->cardIdentifier = json_encode([
            "cardIdentifier" => "336AEE65-AC64-4C10-AC2F-9ED767C3117F",
            "expiry" => "2019-12-06T11:04:04.501Z",
            "cardType" => "Visa"
        ]);

    }

    /**
     * Display a listing of the resource.
     * @return Response
     * @throws \Exception
     */
    public function index()
    {

//        $msk = new PaymentGateway();
        $msk = new \Modules\Payment\Gateway\Sagepay\Payment();
//        $msk = new \Modules\Payment\Gateway\Sagepay\Repeat();
//        $msk = new \Modules\Payment\Gateway\Sagepay\Refund();
//        $msk = new \Modules\Payment\Gateway\Sagepay\Deferred();
//        dump($msk->paymentOrder(true));
        dump($msk->paymentOrder(false));
//        dump($msk->repeatOrder(false));
//        dump($msk->refundOrder());
//        dump($msk->deferredOrder(false));

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function ci()
    {

        $msk = new Payment();

        $msk->createCardIdentifier();
//        $msk->getCardAuthIdentifier();

        dump($msk);

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
