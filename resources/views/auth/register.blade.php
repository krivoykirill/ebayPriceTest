@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body bg-light row justify-content-center">
                    <div class="col-md-8 text-center">
                        <p class="display-4 text-secondary">Sign up</p>
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="name" type="text" placeholder="name" required />
                                @if ($errors->has('name'))

                                    <div class="invalid-feedback d-block" role="alert">
                                        {{ $errors->first('name') }}
                                    </div>

                                @endif
                            </div>
                            <div class="form-group">
                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="email" type="text" placeholder="email" required/>
                                @if ($errors->has('email'))
                                    @if (strlen($errors->first('email'))!=43)
                                        <div class="invalid-feedback d-block" role="alert">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="password" type="password" placeholder="password" required/>
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback d-block" role="alert">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="w-100 border-radius-30 text-center py-1 no-outline" name="password_confirmation" type="password" placeholder="confirm password" required/>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input p-1" id="agreement" required>
                                <label class="form-check-label" for="agreement">I agree to the <strong class="ebay-color">Terms & Conditions</strong></label>
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
