@extends('layouts.default')
@section('content')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .card-body {
            min-height: 150px;
        }
    </style>
    <div class="page-wrapper">
        <div class="card-group">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('hr/viewEmployeeList?m=' . Input::get('m')) }}" target="_blank">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="huge total_employees text-dark mb-1 font-weight-medium"></h2>

                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Employees</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted hrSection"><i data-feather="user-plus"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('hr/viewEmployeeList?m=' . Input::get('m')) }}" target="_blank">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="huge total_employees_onboard text-dark mb-1 w-100 text-truncate font-weight-medium">
                                    <sup class="set-doller"></sup></h2>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Employees Onboard
                                </h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted hrSection"><i data-feather="trending-up"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('hr/viewDepartmentList?m=' . Input::get('m')) }}" target="_blank">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <div class="d-inline-flex align-items-center">
                                    <h2 class="huge total_departments text-dark mb-1 font-weight-medium"></h2>

                                </div>
                                <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Departments</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted hrSection"><i data-feather="globe"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('hr/viewEmployeeExitClearanceList?m=' . Input::get('m')) }}" target="_blank">
                        <div class="d-flex d-lg-flex d-md-block align-items-center">
                            <div>
                                <h2 class="huge total_employees_exit text-dark mb-1 font-weight-medium"></h2>
                                <h6 class=" text-muted font-weight-normal mb-0 w-100 text-truncate">Employees Exit</h6>
                            </div>
                            <div class="ml-auto mt-md-3 mt-lg-0">
                                <span class="opacity-7 text-muted hrSection"><i data-feather="user-minus"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        {{-- <div class="container"> --}}

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <canvas id="bar" style="width:100%;max-width:600px;background-color:white"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <canvas id="vertical" style="width:100%;max-width:600px;background-color:white"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <canvas id="line" style="width:100%;max-width:600px;background-color:white"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div id="verticalapx" style="width:100%;max-width:600px;background-color:white"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const data = JSON.parse('<?php echo json_encode($data); ?>');


        let charts = (data) => {
            donutChart(data?.employee);
            varticalBar(data?.total_salary?.totalPaySalary);
            lineChart(data?.total_monthly_salary?.dataset);
        }


        let donutChart = (data) => {

            var xValues = [] //["Italy", "France", "Spain", "USA", "Argentina"];
            var yValues = [] //[55, 49, 44, 24, 15];
            var barColors = ['#75f136', '#ffcb53', '#f71d1d', '#00c3c3']//, '#00a4ec'];


            Object.entries(data).forEach(([key, value]) => {
                // console.log(`Key: ${key}, Value: ${value}`);
                xValues.push(key.replace("_"," "));
            yValues.push(value);


        })


        new Chart("bar", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                title: {
                    display: true,
                    text: "Employees"
                }
            }
        });

        }

        let varticalBar = (data) => {

            let label = [];
            let rowSalary = [];
            let rowTax = [];

            Object.entries(data).forEach(([key, value]) => {
                label.push(value.location_name);
            rowSalary.push(value.pay);
            rowTax.push(value.tax_amount);
        })
        new Chart("vertical", {
            type: 'bar',
            data: {
                labels: label, //["Africa", "Asia", "Europe", "Latin America", "North America"],
                datasets: [{
                    label: 'total paid salary',
                    backgroundColor: ["#1a3ef0","#1a3ef0"],

                    data: rowSalary
                }, {
                    label: 'total tax paid',
                    backgroundColor: ["#5fc7ed","#5fc7ed"],

                    data: rowTax
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'total given salary / paid tax'
                }
            }
        });


        }

        let lineChart = (data) => {

            data = [data] ;
            const transformedData = Object.values(data[0][0]);


            new Chart("line", {
                type: "line",
                data: {
                    labels: data[0][1],
                    datasets: transformedData
                },
                options: {
                    legend: {display: true}
                }
            });
        }

        charts(data);



        // var chart = new ApexCharts(document.querySelector("#radialBar"), options);
        // chart.render();

        // var chart = new ApexCharts(document.querySelector("#radialBar"), options);
        // chart.render();

    </script>
@endsection
