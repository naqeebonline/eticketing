@extends('layouts.admin')
@section('title', 'Edit Weekly Schedule')
@section('header', 'Edit Weekly Schedule')
@section('breadcrumb', $plan->route->displayLabel())

@section('content')
@include('admin.schedules._weekly-form', [
    'formAction' => route('admin.schedules.plan.update', $plan),
    'formMethod' => 'PUT',
    'plan' => $plan,
    'initialWeekdays' => $initialWeekdays,
    'formTitle' => 'Edit weekly schedule',
    'formSubtitle' => $plan->vehicle->name.' · '.$plan->route->displayLabel(),
    'submitLabel' => 'Update weekly schedule',
])
@endsection
