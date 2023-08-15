@extends('layouts.admin')
@php
    $settings = Utility::settings();
    $lang = isset($currEmailTempLang->lang) ? $currEmailTempLang->lang : 'en';
    if ($lang == null) {
        $lang = 'en';
    }
    $LangName = \App\Models\Language::where('code', $lang)->first();
    
@endphp
@section('page-title')
    {{ $emailTemplate->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Email Template') }}</li>
@endsection

@push('pre-purpose-css-page')
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush

@push('script-page')
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script>
@endpush

@section('action-btn')
    <!-- <div class="all-button-box row d-flex justify-content-end">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true"  data-bs-toggle="modal"
        data-bs-target="#exampleModal"
                data-title="{{ __('Create New Email Template') }}" data-url="{{ route('email_template.create') }}"><i class="ti ti-plus"></i> {{ __('Add') }} </a>
        </div>
    </div> -->
    <div class="row">
        <!-- <div class="col-lg-6">
                <h3 class="m-2">{{ __($emailTemplate->name) }}</h3>
            </div> -->
        <!-- <div class="col-lg-6"> -->
        <div class="text-end">
            <div class="d-flex justify-content-end drp-languages">
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                            {{-- <i class="ti ti-world nocolor"></i> --}}
                            <span
                                class="drp-text hide-mob text-primary">{{ __('Locale: ') }}{{ ucFirst(isset($LangName->fullName) ? $LangName->fullName : 'English') }}</span>
                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                            @foreach ($languages as $code => $lang)
                                <a href="{{ route('manage.email.language', [$emailTemplate->id, $code]) }}"
                                    class="dropdown-item {{ $currEmailTempLang->lang == $lang ? 'text-primary' : '' }}">{{ Str::upper($lang) }}</a>
                            @endforeach
                        </div>
                    </li>
                </ul>
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                            <span
                                class="drp-text hide-mob text-primary">{{ __('Template: ') }}{{ $emailTemplate->name }}</span>
                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                            @foreach ($EmailTemplates as $EmailTemplate)
                                <a href="{{ route('manage.email.language', [$EmailTemplate->id, Request::segment(3) ? Request::segment(3) : \Auth::user()->lang]) }}"
                                    class="dropdown-item {{ $emailTemplate->name == $EmailTemplate->name ? 'text-primary' : '' }}">{{ $EmailTemplate->name }}
                                </a>
                            @endforeach
                        </div>
                    </li>

                </ul>
                <div class="mb-0 m-2">
                        <a href="#" data-size="md" class="btn btn-primary" data-ajax-popup-over="true"
                            data-url="{{ route('generate', ['email template']) }}" data-bs-placement="top"
                            data-title="{{ __('Generate content with AI') }}">
                            <i class="fas fa-robot"></i> <span>{{ __('Generate with AI') }}</span>
                        </a>
                    </div>
            </div>
        </div>
        <!-- </div> -->
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body ">
                    {{-- <div class="card"> --}}
                    {{ Form::model($currEmailTempLang, ['route' => ['email_template.update', $currEmailTempLang->parent_id], 'method' => 'PUT']) }}
                    <h5>{{ __('Placeholders') }}</h5>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header card-body">
                                <!-- <h5></h5> -->
                                <div class="row text-xs">
                                    @if ($emailTemplate->slug == 'new_bill_payment')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Bill Payment Create') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Payment Name') }} : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4">{{ __('Payment Bill') }} : <span
                                                    class="pull-right text-primary">{payment_bill}</span></p>
                                            <p class="col-4">{{ __('Payment Amount') }} : <span
                                                    class="pull-right text-primary">{payment_amount}</span></p>
                                            <p class="col-4">{{ __('Payment Date') }} : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>
                                            <p class="col-4">{{ __('Payment Method') }} : <span
                                                    class="pull-right text-primary">{payment_method}</span></p>


                                        </div>
                                    @elseif($emailTemplate->slug == 'customer_invoice_sent')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Customer Invoice Send') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Invoice Name') }} : <span
                                                    class="pull-right text-primary">{invoice_name}</span></p>
                                            <p class="col-4">{{ __('Invoice Number') }} : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4">{{ __('Invoice Url') }} : <span
                                                    class="pull-right text-primary">{invoice_url}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'bill_sent')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Bill Send') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Bill Name') }} : <span
                                                    class="pull-right text-primary">{bill_name}</span></p>
                                            <p class="col-4">{{ __('Bill Number') }} : <span
                                                    class="pull-right text-primary">{bill_number}</span></p>
                                            <p class="col-4">{{ __('Bill Url') }} : <span
                                                    class="pull-right text-primary">{bill_url}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'new_invoice_payment')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Invoice payment Create') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Payment Name') }} : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4">{{ __('Payment Amount') }} : <span
                                                    class="pull-right text-primary">{payment_amount}</span></p>
                                            <p class="col-4">{{ __('Invoice Number') }} : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4">{{ __('Payment Date') }} : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>
                                            <p class="col-4">{{ __('Payment DueAmount') }} : <span
                                                    class="pull-right text-primary">{payment_dueAmount}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'invoice_sent')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Invoice Send') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Invoice Name') }} : <span
                                                    class="pull-right text-primary">{invoice_name}</span></p>
                                            <p class="col-4">{{ __('Invoice Number') }} : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4">{{ __('Invoice Url') }} : <span
                                                    class="pull-right text-primary">{invoice_url}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'payment_reminder')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Payment Reminder') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Payment Name') }} : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4">{{ __('Invoice Number') }} : <span
                                                    class="pull-right text-primary">{invoice_number}</span></p>
                                            <p class="col-4">{{ __('Payment Due Amount') }} : <span
                                                    class="pull-right text-primary">{payment_dueAmount}</span></p>
                                            <p class="col-4">{{ __('Payment Date') }} : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'proposal_sent')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Proposal Send') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Proposal Name') }} : <span
                                                    class="pull-right text-primary">{proposal_name}</span></p>
                                            <p class="col-4">{{ __('Proposal Number') }} : <span
                                                    class="pull-right text-primary">{proposal_number}</span></p>
                                            <p class="col-4">{{ __('Proposal Url') }} : <span
                                                    class="pull-right text-primary">{proposal_url}</span></p>
                                        </div>
                                    @elseif($emailTemplate->slug == 'user_created')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Create User') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Email') }} : <span
                                                    class="pull-right text-primary">{email}</span></p>
                                            <p class="col-4">{{ __('Password') }} : <span
                                                    class="pull-right text-primary">{password}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'vendor_bill_sent')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Vendor Bill Send') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Bill Name') }} : <span
                                                    class="pull-right text-primary">{bill_name}</span></p>
                                            <p class="col-4">{{ __('Bill Number') }} : <span
                                                    class="pull-right text-primary">{bill_number}</span></p>
                                            <p class="col-4">{{ __('Bill Url') }} : <span
                                                    class="pull-right text-primary">{bill_url}</span></p>

                                        </div>
                                    @elseif($emailTemplate->slug == 'new_contract')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Create User') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Contract Customer') }} : <span
                                                    class="pull-right text-primary">{contract_customer}</span></p>
                                            <p class="col-4">{{ __('Contract Subject') }} : <span
                                                    class="pull-right text-primary">{contract_subject}</span></p>
                                            <p class="col-4">{{ __('Contract Start_Date') }} : <span
                                                    class="pull-right text-primary">{contract_start_date}</span></p>
                                            <p class="col-4">{{ __('Contract End_Date') }} : <span
                                                    class="pull-right text-primary">{contract_end_date}</span></p>
                                            <p class="col-4">{{ __('Contract Type') }} : <span
                                                    class="pull-right text-primary">{contract_type}</span></p>
                                            <p class="col-4">{{ __('Contract Value') }} : <span
                                                    class="pull-right text-primary">{contract_value}</span></p>
                                        </div>
                                    @elseif($emailTemplate->slug == 'retainer_sent' || $emailTemplate->slug == 'customer_retainer_sent')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Proposal Send') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Retainer Name') }} : <span
                                                    class="pull-right text-primary">{retainer_name}</span></p>
                                            <p class="col-4">{{ __('Retainer Number') }} : <span
                                                    class="pull-right text-primary">{retainer_number}</span></p>
                                            <p class="col-4">{{ __('Retainer Url') }} : <span
                                                    class="pull-right text-primary">{retainer_url}</span></p>
                                        </div>
                                    @elseif($emailTemplate->slug == 'new_retainer_payment')
                                        <div class="row">
                                            <!-- <h6 class="font-weight-bold pb-3">{{ __('Invoice payment Create') }}</h6> -->
                                            <p class="col-4">{{ __('App Name') }} : <span
                                                    class="pull-end text-primary">{app_name}</span></p>
                                            <p class="col-4">{{ __('Company Name') }} : <span
                                                    class="pull-right text-primary">{company_name}</span></p>
                                            <p class="col-4">{{ __('App Url') }} : <span
                                                    class="pull-right text-primary">{app_url}</span></p>
                                            <p class="col-4">{{ __('Payment Name') }} : <span
                                                    class="pull-right text-primary">{payment_name}</span></p>
                                            <p class="col-4">{{ __('Payment Amount') }} : <span
                                                    class="pull-right text-primary">{payment_amount}</span></p>
                                            <p class="col-4">{{ __('Retainer Number') }} : <span
                                                    class="pull-right text-primary">{retainer_number}</span></p>
                                            <p class="col-4">{{ __('Payment Date') }} : <span
                                                    class="pull-right text-primary">{payment_date}</span></p>
                                            <p class="col-4">{{ __('Payment DueAmount') }} : <span
                                                    class="pull-right text-primary">{payment_dueAmount}</span></p>

                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-6">
                            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('subject', null, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('from', __('From'), ['class' => 'col-form-label text-dark']) }}
                            {{ Form::text('from', $emailTemplate->from, ['class' => 'form-control font-style', 'required' => 'required']) }}
                        </div>
                        <div class="form-group col-12">
                            {{ Form::label('content', __('Email Message'), ['class' => 'form-label text-dark']) }}
                            {{ Form::textarea('content', $currEmailTempLang->content, ['class' => 'pc-tinymce-2', 'required' => 'required']) }}
                        </div>
                    </div>


                    <hr>
                    <div class="col-md-12 text-end">
                        {{ Form::hidden('lang', null) }}
                        <input type="submit" value="{{ __('Save') }}"
                            class="btn btn-print-invoice  btn-primary m-r-10">
                    </div>

                    {{ Form::close() }}



                </div>
            </div>
        </div>
    </div>
@endsection
