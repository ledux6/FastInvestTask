@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Hello {{ Auth::user()->name}},<br>
                    Your account number is: <br>
                    <h2>{{ $account_number }}</h2>
                    Balance: <b>€{{ $balance/1000}}</b>
                    <form method="get" action="/transfer">
                        <button type="submit">Continue</button>
                    </form>
                </div>
                
            </div>
            <div class="card">
                <div class="card-header">Inbound transactions:</div>
                <div class="card-body">
                    <table class="table table-dark">
                        <tr>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                        </tr>
                    @foreach($inbound as $in)
                        <tr>
                        <td scope="row">{{$in->from}}</td>
                        <td>{{$in->to}}</td>
                        <td>{{$in->amount/1000}}</td>
                        <td>{{$in->created_at}}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Outbound transactions:</div>
                <div class="card-body">
                    <table class="table table-dark">
                        <tr>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                        </tr>
                    @foreach($outbound as $out)
                        <tr>
                        <td scope="row">{{$out->from}}</td>
                        <td>{{$out->to}}</td>
                        <td>{{$out->amount/1000}}</td>
                        <td>{{$out->created_at}}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
