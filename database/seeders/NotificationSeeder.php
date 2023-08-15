<?php

namespace Database\Seeders;

use App\Models\NotificationTemplateLangs;
use App\Models\NotificationTemplates;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notifications = [
            'new_customer' => 'New Customer',
            'new_invoice' => 'New Invoice',
            'new_bill' => 'New Bill',
            'new_vendor' => 'New Vendor',
            'new_revenue' => 'New Revenue',
            'new_proposal' => 'New Proposal',
            'new_payment' => 'New Payment',
            'invoice_reminder' => 'Invoice Reminder',

        ];


        $defaultTemplate = [
            'new_customer' => [
                'variables' => '{
                    "Customer Name": "customer_name",
                    "Email": "email",
                    "Password": "password", 
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                    'ta' => 'New Customer created by {customer_name}',
                  
                    'en' => 'New Customer created by {customer_name}',
                 
                ]
            ],
            'new_invoice' => [
                'variables' => '{
                    "Invoice Name": "invoice_name",
                    "Invoice Number": "invoice_number",
                    "Invoice URL": "invoice_url",
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                    'ta' => 'New Invoice {invoice_number} created by {invoice_name}',
                    'en' => 'New Invoice {invoice_number} created by {invoice_name}',
                   
                ],
            ],
            'new_bill' => [
                'variables' => '{
                    "Bill Name": "bill_name",
                    "Bill Number": "bill_number",
                    "Bill Url": "bill_url",
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                    
                    'ta' => 'New Bill {bill_number} created by {bill_name}',
                    'en' => 'New Bill {bill_number} created by {bill_name}',
                   
                ],
            ],
            'new_vendor' => [
                'variables' => '{
                    "Vendor Name": "vender_name",
                    "Email": "email",
                    "Password": "password", 
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                    
                    'ta' => 'New Vendor created by {vender_name}',
                    'en' => 'New Vendor created by {vender_name}',
                    
                ],
            ],
            'new_revenue' => [
                'variables' => '{
                    "Revenue name": "payment_name",
                    "Amount": "payment_amount",
                    "Payment Date": "payment_date",
                    "Company Name": "user_name",
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
             
                    'en' => 'New Revenue of {payment_amount} created for {payment_name} by {user_name}',
                    'en' => 'New Revenue of {payment_amount} created for {payment_name} by {user_name}',
                
                ],
            ],
            'new_proposal' => [
                'variables' => '{
                    "Proposal Name": "proposal_name",
                    "Proposal Number": "proposal_number",
                    "Proposal Url": "proposal_url",
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                  
                    'ta' => 'New Proposal created by {proposal_name}',
                    'en' => 'New Proposal created by {proposal_name}',
         
                ],
            ],
            'new_payment' => [
                'variables' => '{
                    "Payment Name": "payment_name",
                    "Payment Amount": "payment_amount",
                    "Payment Type": "type", 
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                
                    'ta' => 'New payment of {payment_amount} created for {payment_name} by {type}',
                    'en' => 'New payment of {payment_amount} created for {payment_name} by {type}',
                  
                ],
            ],
            'invoice_reminder' => [
                'variables' => '{
                    "Payment Name": "payment_name",
                    "Invoice Number": "invoice_number",
                    "Payment Due Amount": "payment_dueAmount",
                    "Payment Date": "payment_date",
                    "Company Name": "company_name",
                    "App Name": "app_name",
                    "App Url": "app_url"
                    }',
                'lang' => [
                 
                    'ta' => 'New Payment Reminder of {invoice_number} created by {payment_name}',
                    'en' => 'New Payment Reminder of {invoice_number} created by {payment_name}',
                  
                ],
            ],
        ];

        $user = User::where('type','company')->first();
        foreach($notifications as $k => $n)
        {
            $ntfy = NotificationTemplates::where('slug',$k)->count();
            if($ntfy == 0)
            {
                $new = new NotificationTemplates();
                $new->name = $n;
                $new->slug = $k;
                $new->save();

                foreach($defaultTemplate[$k]['lang'] as $lang => $content)
                {
                    NotificationTemplateLangs::create(
                        [
                            'parent_id' => $new->id,
                            'lang' => $lang,
                            'variables' => $defaultTemplate[$k]['variables'],
                            'content' => $content,                                                      
                            'created_by' => $user->id,
                        ]
                    );
                }
            }
        }
    }
}
