@extends('common.template')

@section('heading')
    {{ $campaign->name }}
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-envelope-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-number">{{ $campaign->email->sent_count }}</span>
                    <span class="info-box-text">Emails Sent</span>
                </div>
            </div>
        </div>

        <div class="col-sm-4 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-envelope-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-number">{{ $uniqueOpenCount }} ({{ round($campaign->email->open_ratio * 100, 1) }}%)</span>
                    <span class="info-box-text">Unique Opens</span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-envelope-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-number">{{ round($campaign->email->click_ratio * 100, 1) }}%</span>
                    <span class="info-box-text">Click Rate</span>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Opens Per Hour</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="opensChart" style="height: 230px; width: 755px;" height="230" width="755"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Top Clicked Links</h3>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tbody>
                            @foreach($campaignLinks as $url)
                                <tr>
                                    <td>{{ $url->link }}</td>
                                    <td>{{ $url->click_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')
    <script src="{{ asset('js/Chart.bundle.js') }}"></script>

    <script>
        $(function () {
            var ctx = document.getElementById("opensChart");
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! $chartData['labels'] !!},
                    datasets: [{
                        data: {!! $chartData['data'] !!},
                        label: "Opens",
                        backgroundColor: 'rgba(0,115,183,1)'
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display:false
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }}
            });
        });
</script>
@endsection
