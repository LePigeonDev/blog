@extends('layouts.app')

@section('title','Mon compte')

@section('content')
  <h1 class="text-2xl font-bold mb-6">Mon compte</h1>

  @include('profile.partials.update-profile-information-form', ['user' => $user])
  @include('profile.partials.update-password-form')
  @include('profile.partials.delete-user-form')
@endsection
