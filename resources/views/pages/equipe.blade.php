@extends('pages.layouts.userapp')
@auth
    {{ Auth::user()->name}}
@endauth
