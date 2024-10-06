@extends('layout')
@section('main')
<script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$responseData['ndc']}}"></script>
<form action="{{'project.details',$sphere_id,$project_id}}" class="paymentWidgets" data-brands="VISA MASTER MADA"></form>

@stop