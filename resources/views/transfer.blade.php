@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Make a Transfer</div>
                <div class="card-body">
                    <form action="/make-transfer" method="post">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(isset($er))
                            <div>{{$er}}</div>
                            <div>
                                <a href="/home">Go back>></a>
                            </div>
                        @else
                        <div class="form-group">
                            <label>Transfer amount</label>
                            <input type="text" class="form-control"  placeholder="0.00" name="amount">
                            <small class="form-text text-muted">Your balance: @if($balance!=0) {{$balance/100}} @endif</small>
                        </div>
                        <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" class="form-control" placeholder="8888888888888888" name="account">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection