@extends('common.template')

@section('heading')
    {{ $newsletter->name }}
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-2 col-sm-4 col-xs-12">
            <div class="info-box">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Emails Sent</span>
                    <span class="info-box-number">{{ $newsletter->sent_count }}</span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div>

        <div class="col-lg-2 col-sm-4 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $newsletter->sent_count }}</h3>

                    <p>Emails Sent</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <p>Open Rate</p>

                    <h3>{{ round($newsletter->open_ratio * 100, 1) }}<sup style="font-size: 20px">%</sup></h3>

                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-xs-12">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <p>Click Rate</p>

                    <h3>{{ round($newsletter->click_ratio * 100, 1) }}<sup style="font-size: 20px">%</sup></h3>

                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>

@endsection
