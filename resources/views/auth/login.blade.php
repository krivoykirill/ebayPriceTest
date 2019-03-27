@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                

                <div class="card-body bg-light row justify-content-center">
                    <div class="col-md-8 text-center">
                        <p class="display-4 text-secondary">Log in</p>
                        <form action="{{route('login')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <input class="form-control w-100 border-radius-30 text-center py-1 no-outline" name="email" type="text" placeholder="email" />


                                @if ($errors->has('email'))
                                    @if (strlen($errors->first('email'))==43)
                                        <div class="invalid-feedback d-block" role="alert">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                @endif


                            </div>

                            <div class="form-group">
                                <input class="w-100 border-radius-30 text-center py-1 no-outline" type="password" name="password" placeholder="password" />
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember me on this computer') }}
                                </label>
                            </div>

                            <button type="submit" class="btn btn-outline-success cta-inverted w-100 p-2">Start analyzing</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
