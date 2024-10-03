@extends('layouts.app')

@section('content')
    <main-component
            :authUser="{{Auth::user()}}"
    />
@endsection
