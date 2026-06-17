<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

@section('title', 'Philippine Society of Anesthesiologists')
@extends('layouts.app')

@section('content')
     {{-- <livewire:gallery-component /> --}}
         <livewire:event-registration.registered-list />

@endsection