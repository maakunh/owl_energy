@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">ユーザ情報</div>
    <div class="panel-body">
        <table class="table">
            <tbody>
                <tr>
                    <td> <div>ユーザ名：</div></td>
                    <td> <div>{{ Auth::user()->name }}</div></td>
                </tr>
                <tr>
                    <td> <div>メールアドレス：</div></td>
                    <td> <div>{{ Auth::user()->email}}</div></td>
                </tr>
                <tr>
                    <td> <div>API Key：</div></td>
                    <td> <div>{{ Auth::user()->password }}</div></td>
                </tr>
             </tbody>
        </table>
        
    </div>
    </div>
</div>
<footer class="text-right container-fluid bFoot">
    <div class="container">
<div>ver: {{ config('owl_energy.VERSION') }}</div>
    </div>
</footer>

@endsection

