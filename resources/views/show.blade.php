@extends('layouts.app')

@section('content')
    <div class="container">
    <h1>Title: {{$query->keywords}}</h1>
    <p>Last scan at: {{$query->last_check}}</p>
    <img src="{{$query->thumbnail}}" alt="thumbnail"/>
    </div>
    
@endsection