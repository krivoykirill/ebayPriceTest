@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Home Page</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        @forelse ($queries as $query)
                            <div class="card mr-2 mb-2 text-center" style="width: 10rem;">
                                <div class="card-body">
                                    <h5 class="card-title">{{$query->type}}</h5>
                                    <p class="card-text">{{$query->type_value}}</p>
                                    <a href="{{url('view/$query->id')}}" class="
                                    @if ($query->type=='product')
                                        {{'btn btn-success'}}
                                    @else 
                                        {{'btn btn-danger'}}
                                    @endif
                                        ">Open</a>
                                </div>
                            </div>
                        @empty
                            <p>No output</p>                        
                        @endforelse
                        <div class="card mr-2 mb-2 text-center my-auto" style="width: 10rem;">
                            <div class="card-body jumbotron">
                                <a class="btn btn-primary align-middle" href="{{url('add')}}">Add Query</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
