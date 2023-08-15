<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Retainer;
use App\Models\RetainerPayment;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe;

class StripePaymentController extends Controller
{
    public $settings;


    public function index()
    {
        $objUser = \Auth::user();
        if($objUser->type == 'super admin')
        {
            $orders = Order::select(
                [
                    'orders.*',
                    'users.name as user_name',
                ]
            )->join('users', 'orders.user_id', '=', 'users.id')->orderBy('orders.created_at', 'DESC')->get();
        }
        else
        {
            $orders = Order::select(
                [
                    'orders.*',
                    'users.name as user_name',
                ]
            )->join('users', 'orders.user_id', '=', 'users.id')->orderBy('orders.created_at', 'DESC')->where('users.id', '=', $objUser->id)->get();
        }

        return view('order.index', compact('orders'));
    }

   

    public function addPayment(Request $request, $id)
    {

        $invoice = Invoice::find($id);
        if (Auth::check()) {
        $user_id = \Auth::user()->creatorId();
        }
        if(Auth::check()){
            $company_payment_setting = Utility::getCompanyPaymentSetting($user_id);
            $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');
            $objUser = \Auth::user();
        }else{
            $user = User::where('id',$invoice->created_by)->first();
            $company_payment_setting = Utility::getCompanyPaymentSetting($user->id);
            // dd($company_payment_setting);
            $settings = Utility::settingById($invoice->created_by);
            $objUser = $user;
        }


        if($invoice)
        {
            if($request->amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {


                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $price   = $request->amount;
                    Stripe\Stripe::setApiKey($company_payment_setting['stripe_secret']);
                    // dd(env('CURRENCY'));

                    $data = Stripe\Charge::create(
                        [
                            "amount" => 100 * $price,
                            "currency" => !empty(env('CURRENCY')) ? env('CURRENCY') : 'INR',
                            "source" => $request->stripeToken,
                            "description" => 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                            "metadata" => ["order_id" => $orderID],
                        ]

                    );

                    if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                    {
                        $payments = InvoicePayment::create(
                            [

                                'invoice_id' => $invoice->id,
                                'date' => date('Y-m-d'),
                                'amount' => $price,
                                'account_id' => 0,
                                'payment_method' => 0,
                                'order_id' => $orderID,
                                'currency' => $data['currency'],
                                'txn_id' => $data['balance_transaction'],
                                'payment_type' => __('STRIPE'),
                                'receipt' => null,
                                'add_receipt' => $data['receipt_url'],
                                'reference' => '',
                                'description' => 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id),
                            ]
                        );


                        if($invoice->getDue() <= 0)
                        {
                            $invoice->status = 4;
                        }
                        elseif(($invoice->getDue() - $request->amount) == 0)
                        {
                            $invoice->status = 3;
                        }
                        else
                        {
                            $invoice->status = 2;
                        }
                        $invoice->save();

                        $invoicePayment              = new Transaction();
                        $invoicePayment->user_id     = $invoice->customer_id;
                        $invoicePayment->user_type   = 'Customer';
                        $invoicePayment->type        = 'STRIPE';
                        $invoicePayment->created_by  = $objUser->id;
                        $invoicePayment->payment_id  = $invoicePayment->id;
                        $invoicePayment->category    = 'Invoice';
                        $invoicePayment->amount      = $price;
                        $invoicePayment->date        = date('Y-m-d');
                        $invoicePayment->created_by  = $objUser->creatorId();
                        $invoicePayment->payment_id  = $payments->id;
                        $invoicePayment->description = 'Invoice ' . Utility::invoiceNumberFormat($settings, $invoice->invoice_id);
                        $invoicePayment->account     = 0;

                        Transaction::addTransaction($invoicePayment);
                       
                        Utility::userBalance('customer', $invoice->customer_id, $request->amount, 'debit');
                        // dd(Utility::bankAccountBalance($request->account_id, $request->amount, 'credit'));
                        Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

                        // Twilio Notification
                        
                        $setting  = Utility::settingsById($objUser->creatorId());
                        $customer = Customer::find($invoice->customer_id);
                        if(isset($setting['payment_notification']) && $setting['payment_notification'] ==1)
                        {
                            $uArr = [
                                'invoice_id' => $invoice->id,
                                'payment_name' => $customer->name,
                                'payment_amount' => $price,
                                'payment_date' => $objUser->dateFormat($request->date),
                                'type' => 'STRIPE',
                                'user_name' => $objUser->name,
                            ];

                            Utility::send_twilio_msg($customer->contact,'new_payment', $uArr, $invoice->created_by);
                        }

                         // webhook
                         $module = 'New Payment';

                         $webhook =  Utility::webhookSetting($module,$invoice->created_by);
 
                         if ($webhook) {
 
                             $parameter = json_encode($invoice);
 
                             // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
 
                             $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
 
                             // if ($status == true) {
                             //     return redirect()->route('payment.index')->with('success', __('Payment successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
                             // } else {
                             //     return redirect()->back()->with('error', __('Webhook call failed.'));
                             // }
                         }

                        if (Auth::check()) {
                            return redirect()->route('invoice.show', \Crypt::encrypt($invoice->id))->with('success', __('Payment successfully added'));
                        } else {
                            return redirect()->back()->with('success', __(' Payment successfully added.'));
                        }
                    }
                    else
                    {
                        if (Auth::check()) {
                            return redirect()->route('invoice.show', \Crypt::encrypt($invoice->id))->with('error', __('Transaction has been ' . $status));
                        } else {
                            return redirect()->back()->with('success', __('Transaction succesfull'));
                        }
                    }

            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function addretainerpayment(Request $request, $id)
    {
        $retainer = Retainer::find($id);
        if (Auth::check()) {
        $user_id = \Auth::user()->creatorId();
        }
        if(Auth::check()){
            $company_payment_setting = Utility::getCompanyPaymentSetting($user_id);
            $settings = DB::table('settings')->where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('value', 'name');
            $objUser = \Auth::user();
        }else{
            $user = User::where('id',$retainer->created_by)->first();
            $company_payment_setting = Utility::getCompanyPaymentSetting($user);
            $settings = Utility::settingById($retainer->created_by);
            $objUser = $user;
        }


        if($retainer)
        {
            if($request->amount > $retainer->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {


                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $price   = $request->amount;
                    Stripe\Stripe::setApiKey($company_payment_setting['stripe_secret']);
                    // @dd(env('CURRENCY'));

                    $data = Stripe\Charge::create(
                        [
                            "amount" => 100 * $price,
                            "currency" => 'INR',
                            "source" => $request->stripeToken,
                            "description" => 'Retainer ' . Utility::retainerNumberFormat($settings, $retainer->retainer_id),
                            "metadata" => ["order_id" => $orderID],
                        ]

                    );


                    if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                    {
                        $payments = RetainerPayment::create(
                            [

                                'retainer_id' => $retainer->id,
                                'date' => date('Y-m-d'),
                                'amount' => $price,
                                'account_id' => 0,
                                'payment_method' => 0,
                                'order_id' => $orderID,
                                'currency' => $data['currency'],
                                'txn_id' => $data['balance_transaction'],
                                'payment_type' => __('STRIPE'),
                                'receipt' => null,
                                'add_receipt' => $data['receipt_url'],
                                'reference' => '',
                                'description' => 'Retainer ' . Utility::retainerNumberFormat($settings, $retainer->retainer_id),
                            ]

                        );


                        if($retainer->getDue() <= 0)
                        {
                            $retainer->status = 4;
                        }
                        elseif(($retainer->getDue() - $request->amount) == 0)
                        {
                            $retainer->status = 3;
                        }
                        else
                        {
                            $retainer->status = 2;
                        }
                        
                        $retainer->save();

                        $retainerPayment              = new Transaction();
                        $retainerPayment->user_id     = $retainer->customer_id;
                        $retainerPayment->user_type   = 'Customer';
                        $retainerPayment->type        = 'STRIPE';
                        $retainerPayment->created_by  = $objUser->id;
                        $retainerPayment->payment_id  = $retainerPayment->id;
                        $retainerPayment->category    = 'Retainer';
                        $retainerPayment->amount      = $price;
                        $retainerPayment->date        = date('Y-m-d');
                        $retainerPayment->created_by  = $objUser->creatorId();
                        $retainerPayment->payment_id  = $payments->id;
                        $retainerPayment->description = 'Retainer ' . Utility::retainerNumberFormat($settings, $retainer->retainer_id);
                        $retainerPayment->account     = 0;
                        Transaction::addTransaction($retainerPayment);



                        Utility::userBalance('customer', $retainer->customer_id, $request->amount, 'debit');

                        Utility::bankAccountBalance($request->account_id, $request->amount, 'credit');

                        //Twilio Notification
                        $setting  = Utility::settingsById($objUser->creatorId());
                        $customer = Customer::find($retainer->customer_id);
                        if(isset($setting['payment_notification']) && $setting['payment_notification'] ==1)
                        {
                            $uArr = [
                                'retainer_id' => $retainer->id,
                                'payment_name' => $customer->name,
                                'payment_amount' => $price,
                                'payment_date' => $objUser->dateFormat($request->date),
                                'type' => 'STRIPE',
                                'user_name' => $objUser->name,
                            ];


                            Utility::send_twilio_msg($customer->contact,'new_payment', $uArr, $retainer->created_by);
                        }

                        // webhook
                        $module = 'New Payment';

                        $webhook =  Utility::webhookSetting($module,$retainer->created_by);

                        if ($webhook) {

                            $parameter = json_encode($retainer);

                            // 1 parameter is  URL , 2 parameter is data , 3 parameter is method

                            $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                            // if ($status == true) {
                            //     return redirect()->route('payment.index')->with('success', __('Payment successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
                            // } else {
                            //     return redirect()->back()->with('error', __('Webhook call failed.'));
                            // }
                        }

                        if (Auth::check()) {
                            return redirect()->route('retainer.show', \Crypt::encrypt($retainer->id))->with('success', __('Payment successfully added'));
                        } else {
                            return redirect()->back()->with('success', __(' Payment successfully added.'));
                        }
                    }
                    else
                    {
                        if (Auth::check()) {
                            return redirect()->route('customer.retainer.show', \Crypt::encrypt($retainer->id))->with('error', __('Transaction has been ' . $status));
                        } else {
                            return redirect()->back()->with('success', __('Transaction succesfull'));
                        }
                    }

            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function invoicePayWithStripe(Request $request)
    {
        $amount = $request->amount;


        $validatorArray = [
            'amount' => 'required',
            'invoice_id' => 'required',
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        )->setAttributeNames(
            ['invoice_id' => 'Invoice']
        );
        if($validator->fails())
        {
            return Utility::error_res($validator->errors()->first());
        }

        $invoice = Invoice::find($request->invoice_id);
        if(Auth::check()){
            $settings = Utility::settings();
        }else{
            $settings = Utility::settingById($invoice->created_by);
        }

        $this->paymentSetting($invoice->created_by);

        $amount = number_format((float)$request->amount, 2, '.', '');

        $invoice_getdue = number_format((float)$invoice->getDue(), 2, '.', '');

        if($invoice_getdue < $amount){
            return Utility::error_res('not correct amount');
        }

        try
        {


            $stripe_formatted_price = in_array(
                $this->currancy, [
                                   'MGA',
                                   'BIF',
                                   'CLP',
                                   'PYG',
                                   'DJF',
                                   'RWF',
                                   'GNF',
                                   'UGX',
                                   'JPY',
                                   'VND',
                                   'VUV',
                                   'XAF',
                                   'KMF',
                                   'KRW',
                                   'XOF',
                                   'XPF',
                               ]
            ) ? number_format($amount, 2, '.', '') : number_format($amount, 2, '.', '') * 100;

            $return_url_parameters = function ($return_type){
                return '&return_type=' . $return_type . '&payment_processor=stripe';
            };

            /* Initiate Stripe */
            \Stripe\Stripe::setApiKey($this->stripe_secret);



            $stripe_session = \Stripe\Checkout\Session::create(
                [
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'name' => $settings['company_name'] . " - " . Utility::invoiceNumberFormat($invoice->invoice_id),
                            'description' => 'payment for Invoice',
                            'amount' => $stripe_formatted_price,
                            'currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'INR',
                            'quantity' => 1,
                        ],
                    ],
                    'metadata' => [
                        'invoice_id' => $request->invoice_id,
                    ],
                    'success_url' => route(
                        'invoice.stripe', [
                                            'invoice_id' => encrypt($request->invoice_id),
                                            'TXNAMOUNT' => $amount,
                                            $return_url_parameters('success'),
                                        ]
                    ),
                    'cancel_url' => route(
                        'invoice.stripe', [
                                            'invoice_id' => encrypt($request->invoice_id),
                                            'TXNAMOUNT' => $amount,
                                            $return_url_parameters('cancel'),
                                        ]
                    ),
                ]
            );


            $stripe_session = $stripe_session ?? false;

            try{
                return new RedirectResponse($stripe_session->url);
            }catch(\Exception $e)
            {
                \Log::debug($e->getMessage());
                return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction has been failed!'));
            }
        }
        catch(\Exception $e)
        {
            \Log::debug($e->getMessage());
            return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction has been failed!'));
        }
    }
}
