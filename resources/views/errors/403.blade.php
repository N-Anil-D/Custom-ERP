@extends('errors::minimal')

@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))