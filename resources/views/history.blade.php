@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-xs-6">
           <div class="panel panel-default">
                <div class="panel-heading">表示したいデータの日付</div>
                <div class="panel-body">
                    <!-- 日付指定フォーム -->
                     <form action="{{ url('/')}}" method="POST" class="form-horizontal">
                          {{csrf_field()}}
                          <!-- 日付指定-->
                            <div class="form-group">
                               <div class="col-sm-6">
                                    <input type="date" name="item_date" id="item_date" class="form-control" value="{{$inputdate}}">
                                    <button type="submit" class="btn btn-default">
                                    
                                    この日を表示 </button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">お知らせ</div>
                <div class="panel-body">
                    <table  class="table">
                   @foreach($owl_infos as $owl_info)
                        <tr>
                            <td>{{ $owl_info->inputdate }}</td>
                            <td>{{ $owl_info->description }}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="panel panel-default">
                <div class="panel-heading">集計結果</div>
                <div class="panel-body">
                    <table  class="table">
                        <th>電力使用量(kWh)</th><th>前月</th><th>当月</th>
                        <tr>
                            <td></td>
                            <td>{{ round($yday_kwh_sum_all, 2) }}</td>
                            <td>{{ round($tday_kwh_sum_all, 2) }}</td>
                        </tr>
                    </table>
                    <table class="table">
                        <th>電気料金(円）</th><th>前月</th><th>当月</th>
                        <tr>
                            <td>基本料金</td>
                            <td colspan="2">{{ $tarif_base }}</td>
                        </tr>
                        <tr>
                            <td>1段階</td>
                            <td>{{ round($y_tarif1, 2) }}</td>
                            <td>{{ round($t_tarif1, 2) }}</td>
                        </tr>
                        <tr>
                            <td>2段階</td>
                            <td>{{ round($y_tarif2, 2) }}</td>
                            <td>{{ round($t_tarif2, 2) }}</td>
                        </tr>
                        <tr>
                            <td>3段階</td>
                            <td>{{ round($y_tarif3, 2) }}</td>
                            <td>{{ round($t_tarif3, 2) }}</td>
                        </tr>
                        <tr>
                            <td>燃料費調整額</td>
                            <td>{{ round($y_tarif_fuel_adj, 2) }}</td>
                            <td>{{ round($t_tarif_fuel_adj, 2) }}</td>
                        </tr>
                        <tr>
                            <td>基本料金＋電力量料金＋燃料費調整額</td>
                            <td>{{ (int)$y_tarif_total }}</td>
                            <td>{{ (int)$t_tarif_total }}</td>
                        </tr>
                    </table>
               </div>
            </div>
        </div>
    </div>


@include('common.errors')

<div class="panel panel-default">
    <div class="panel-body">

            <script type='text/javascript' src='https://www.google.com/jsapi'></script>
            <script type='text/javascript'>
                google.load('visualization', '1', {packages:['corechart']});
                google.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'time');
                    data.addColumn('number', '当日');
                    data.addColumn('number', '前日');
                    data.addRows(1440);
            
                        @foreach($graph_histories as $graph_history)
                                data.setValue({{$loop->index}},0,'{{$graph_history['hour']}}:{{$graph_history['min']}}');
                                data.setValue({{$loop->index}},1,{{$graph_history['tday_kwh']}});
                                data.setValue({{$loop->index}},2,{{$graph_history['yday_kwh']}});
                        @endforeach
            
                    var chart = new google.visualization.LineChart(document.getElementById('chart_div1'));
                    var options = {width : 900, height : 300, title : '１分毎の電力使用量',vAxis: {title: 'kW',maxValue: 4.00},hAxis: {title: '時間',showTextEvery: 60,slantedText: true}};
                    chart.draw(data, options);
                }
            
            </script>
            

            <p align=center><div id='chart_div1'></div></p>

            <script type='text/javascript' src='https://www.google.com/jsapi'></script>
            <script type='text/javascript'>
                google.load('visualization', '1', {packages:['corechart']});
                google.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'hour');
                    data.addColumn('number', '当日');
                    data.addColumn('number', '前日');
                    data.addRows(24);
            
                        @foreach($graph_histories_h as $graph_history_h)
                                data.setValue({{$loop->index}},0,'{{$graph_history_h['hour']}}');
                                data.setValue({{$loop->index}},1,{{$graph_history_h['tday_kwh']}});
                                data.setValue({{$loop->index}},2,{{$graph_history_h['yday_kwh']}});
                        @endforeach
            
                    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
                    var options = {width : 900, height : 300, title : '１時間毎の電力使用量',vAxis: {title: 'kWh',maxValue: 4.00},hAxis: {title: '時',showTextEvery: 1,slantedText: true}};
                    chart.draw(data, options);
                }
            
            </script>
            
            <br />
            <p align=center><div id='chart_div2'></div></p>

            <script type='text/javascript' src='https://www.google.com/jsapi'></script>
            <script type='text/javascript'>
                google.load('visualization', '1', {packages:['corechart']});
                google.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'day');
                    data.addColumn('number', '当月');
                    data.addColumn('number', '前月');
                    data.addRows(31);
            
                        @foreach($graph_histories_m as $graph_history_m)
                                data.setValue({{$loop->index}},0,'{{$graph_history_m['day']}}');
                                data.setValue({{$loop->index}},1,{{$graph_history_m['tday_kwh']}});
                                data.setValue({{$loop->index}},2,{{$graph_history_m['yday_kwh']}});
                        @endforeach

                    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
                    var options = {width : 900, height : 300, title : '１日毎の電力使用量',vAxis: {title: 'kWh',maxValue: 50.00},hAxis: {title: '日',showTextEvery: 1,slantedText: true}};
                    chart.draw(data, options);
                }
            
            </script>
            
            <br />
            <p align=center><div id='chart_div3'></div></p>
    </div>
</div>

<footer class="text-right container-fluid bFoot">
			<div class="container">
    <div>デバイス: {{ $addr }} ver: {{ config('owl_energy.VERSION') }}</div>
    </div>
</footer>
@endsection

