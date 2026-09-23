@extends('admin.layouts.app')
@section('title')
<title>Dashboard</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Order</h4>
                            </div>
                            <div class="card-body">
                                {{$totalOrder}}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Customer</h4>
                            </div>
                            <div class="card-body">
                                {{$totalCustomer}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>New Customer</h4>
                            </div>
                            <div class="card-body">
                                {{$newCustomer}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Service Professional</h4>
                            </div>
                            <div class="card-body">
                                {{$totalVendor}}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pending Order</h4>
                            </div>
                            <div class="card-body">
                                {{$pendingOrder}}
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Complete Order</h4>
                            </div>
                            <div class="card-body">
                                {{$completeOrder}}
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Booking Amount</h4>
                            </div>
                            <div class="card-body">
                                {{$totalBookingAmount}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Purchase Subscription</h4>
                            </div>
                            <div class="card-body">
                                {{$purchaseHistory}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-wrap">
                            <div class="card-header mb-4">
                                <h4>Subscription buy last 7 days</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="myChart2"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Report</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="myChart3"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<script>
    "use strict"
    var ctx2 = document.getElementById("myChart2").getContext("2d");
var myChart = new Chart(ctx2, {
    type: "bar",
    data: {
        labels: [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ],
        datasets: [{
            label: "Statistics",
            data: [460, 458, 330, 502, 430, 610, 488],
            // data: {!! json_encode($purchaseDayCount) !!}, // ✅ use Blade properly
            borderWidth: 2,
            backgroundColor: "#6777ef",
            borderColor: "#6777ef",
            borderWidth: 2.5,
            pointBackgroundColor: "#ffffff",
            pointRadius: 4,
        }],
    },
    options: {
        legend: {
            display: false,
        },
        scales: {
            yAxes: [{
                gridLines: {
                    drawBorder: false,
                    color: "#f2f2f2",
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 100,
                },
            }],
            xAxes: [{
                ticks: {
                    display: false,
                },
                gridLines: {
                    display: false,
                },
            }],
        },
    },
});


    var ctx3 = document.getElementById("myChart3").getContext("2d");
    var myChart = new Chart(ctx3, {
        type: "doughnut",
        data: {
            datasets: [
                {
                    data: [80, 50, 40, 30, 20],
                    backgroundColor: [
                        "#191d21",
                        "#63ed7a",
                        "#ffa426",
                        "#fc544b",
                        "#6777ef",
                    ],
                    label: "Dataset 1",
                },
            ],
            labels: ["Order", "Customer", "Vendor", "Subscription", "Services"],
        },
        options: {
            responsive: true,
            legend: {
                position: "bottom",
            },
        },
    });
</script>
@endsection
