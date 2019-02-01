@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add new query</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{url('/add/new')}}" method="post">
                        <div class="form-group" >
                            <label for="keywords">Enter keywords: </label>
                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="iPhone 7 Plus 32GB Unlocked" required>
                        </div>
                        <div class="form-group">
                            <label for="searchType">Search type: </label>
                            <select name="search_type" class="form-control" id="searchType">
                                <option value="keywords">Search by keywords</option>
                                <option value="product">Search by product</option>
                            </select>
                        </div>
                        <!--
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"></label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                        -->
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <button type="submit" class="btn btn-primary">Add new query</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection