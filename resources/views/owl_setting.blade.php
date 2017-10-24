@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">設定</div>
    <div class="panel-body">
        
        <form action="{{ url('owl_setting/edit')}}" method="POST">
        <table class="table">
            <tbody>
    @if(count($owl_settings) > 0)
        @foreach ($owl_settings as $owl_setting)
                <tr>
                    <td> <div>デバイス：</div></td>
                    <td> <div><input type="text" id="item_addr" name="item_addr" class="form-control" value="{{$owl_setting->addr}}"></div></td>
                </tr>
                <tr>
                    <td> <div>基本料金：</div></td>
                    <td> <div><input type="text" id="item_tarif_base" name="item_tarif_base" class="form-control" value="{{$owl_setting->tarif_base}}"></div></td>
                </tr>
                <tr>
                    <td> <div>1段階単価：</div></td>
                    <td> <div><input type="text" id="item_tarif_1" name="item_tarif_1" class="form-control" value="{{$owl_setting->tarif_1}}"></div></td>
                </tr>
                 <tr>
                    <td> <div>2段階単価：</div></td>
                    <td> <div><input type="text" id="item_tarif_2" name="item_tarif_2" class="form-control" value="{{$owl_setting->tarif_2}}"></div></td>
                </tr>
                <tr>
                    <td> <div>3段階単価：</div></td>
                    <td> <div><input type="text" id="item_tarif_3" name="item_tarif_3" class="form-control" value="{{$owl_setting->tarif_3}}"></div></td>
                </tr>
                 <tr>
                    <td> <div>燃料費調整単価：</div></td>
                    <td> <div><input type="text" id="item_fuel_adj" name="item_fuel_adj" class="form-control" value="{{$owl_setting->fuel_adj}}"></div></td>
                </tr>
        @endforeach
    @endif
    @if(count($owl_settings) == 0)
                <tr>
                    <td> <div>addr：</div></td>
                    <td> <div><input type="text" id="item_addr" name="item_addr" class="form-control" value=""></div></td>
                </tr>
                <tr>
                    <td> <div>基本料金：</div></td>
                    <td> <div><input type="text" id="item_tarif_base" name="item_tarif_base" class="form-control" value=""></div></td>
                </tr>
                <tr>
                    <td> <div>1段階単価：</div></td>
                    <td> <div><input type="text" id="item_tarif_1" name="item_tarif_1" class="form-control" value=""></div></td>
                </tr>
                 <tr>
                    <td> <div>2段階単価：</div></td>
                    <td> <div><input type="text" id="item_tarif_2" name="item_tarif_2" class="form-control" value=""></div></td>
                </tr>
                <tr>
                    <td> <div>3段階単価：</div></td>
                    <td> <div><input type="text" id="item_tarif_3" name="item_tarif_3" class="form-control" value=""></div></td>
                </tr>
                 <tr>
                    <td> <div>燃料費調整単価：</div></td>
                    <td> <div><input type="text" id="item_fuel_adj" name="item_fuel_adj" class="form-control" value=""></div></td>
                </tr>
    @endif
           </tbody>
        </table>
                <div class="well well-sm">
                     <button type="submit" class="btn btn-primary">設定保存</button>
                </div>
              
                <!-- CSRF -->
                {{csrf_field()}}
        </form>    
    </div>
    </div>
</div>
<footer class="text-right container-fluid bFoot">
    <div class="container">
<div>ver: {{ config('owl_energy.VERSION') }} </div><div>{{ $message }}</div>
    </div>
</footer>

@endsection

