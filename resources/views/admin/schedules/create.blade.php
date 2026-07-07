@extends('layouts.admin')
@section('title', 'Add Schedule')
@section('header', 'Add Schedule')
@section('breadcrumb', 'Weekly timetable')

@section('content')
@include('admin.schedules._weekly-form', [
    'formAction' => route('admin.schedules.store'),
    'formTitle' => 'Weekly schedule setup',
    'formSubtitle' => 'Route + bus select karein, phir har weekday ka time — ye config permanent rahegi.',
    'submitLabel' => 'Save weekly schedule',
])
@endsection
