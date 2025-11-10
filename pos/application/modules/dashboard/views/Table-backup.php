<div class="contect card">
    <h1 class="text-center my-3">Dashboard</h1>
    <div id="content-user">
        <div class="contect card mx-3 py-8">
            <div class="row m-3">

                <div class="col-md-6 col-12 mb-3">
                    <div class="card card-custom wave wave-animate-slow wave-primary mb-8 mb-lg-0" style="height:127px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-13">
                                    <span class="svg-icon svg-icon-success svg-icon-4x">
                                        <!-- <svg> -->
                                        <i class="fa fa-arrow-down fa-3x"></i>
                                        <!-- </svg> -->
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-success mb-3">
                                        Drill Hole
                                    </a>
                                    <div class="text-dark-75">
                                        <p class="card-text card-data_total_hole h5">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <div class="card card-custom wave wave-animate-fast wave-success mb-8 mb-lg-0" style="height:127px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-13">
                                    <span class="svg-icon svg-icon-success svg-icon-4x">
                                        <!-- <svg> -->
                                        <i class="fa fa-expand-alt fa-3x"></i>
                                        <!-- </svg> -->
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-primary mb-3">
                                        Total Meter
                                    </a>
                                    <div class="text-dark-75">
                                        <p class="card-text card-data_meter h5">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <div class="card card-custom wave wave-animate-slow wave-warning mb-8 mb-lg-0" style="height:127px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-13">
                                    <span class="svg-icon svg-icon-success svg-icon-4x">
                                        <!-- <svg> -->
                                        <i class="fa fa-calendar-alt fa-3x"></i>
                                        <!-- </svg> -->
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-primary  mb-3">
                                        Number of Days
                                    </a>
                                    <div class="text-dark-75">
                                        <p class="card-text card-total_day h5">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <div class="card card-custom wave wave-animate-fast wave-danger mb-8 mb-lg-0" style="height:127px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-13">
                                    <span class="svg-icon svg-icon-success svg-icon-4x">
                                        <!-- <svg> -->
                                        <i class="fa fa-chart-bar fa-3x"></i>
                                        <!-- </svg> -->
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="#" class="text-dark text-hover-primary mb-3">
                                        Samples
                                    </a>
                                    <div class="text-dark-75">
                                        <p class="card-text card-total_sample h5">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="text-center my-3">Drilling Progress</h1>
        <div class="row">
            <div class="col-sm-3 mx-5">
                <label>Date Range:</label>
                <div class='input-group'>
                    <input type='text' id="daterangepicker" class="form-control" name="search" readonly="readonly" placeholder="Select date range" />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-calendar-check-o"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <label> </label>
                <button type="button" class="btn btn-sm btn-outline-primary w-100 mt-2" onclick="onFilter()"><i class="fa fa-search"></i>Show</button>
            </div>
            <div class="col-5"></div>
            <div class="p-5 col-12 col-md-6">
                <div class="card p-2 m-2">
                    <div class="card-body py-3 px-10">
                        <h2>Total Depth Per Days</h2>
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>
            <div class="p-5 col-12 col-md-6">
                <div class="card p-2 m-2">
                    <div class="card-body py-3 px-10">
                        <h2>Total Depth Accumulation</h2>
                        <div id="chart2"></div>
                    </div>
                </div>
            </div>
            <div class="p-5 col-12 col-md-8">
                <div class="card p-2 m-2">
                    <div class="card-body py-3 px-10">
                        <h2>Drilling Progress Graph</h2>
                        <div id="chart3"></div>
                    </div>
                </div>
            </div>
            <div class="p-5 col-12 col-md-4">
                <!--begin::Engage Widget 2-->
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body d-flex p-0">
                        <div class="flex-grow-1 bg-primary p-8 card-rounded flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: auto 60%; background-image: url(assets/media/svg/humans/custom-3.svg)">
                            <h4 class="text-inverse-primary mt-2 font-weight-bolder">Open Map</h4>
                            <p class="text-inverse-primary my-6">Click here to open map
                                <br>for this project
                            </p>
                            <!-- <a href="#" class="btn btn-warning font-weight-bold py-2 px-6">Learn</a> -->
                            <a type="button" class="btn btn-warning w-50 font-weight-bold py-2 px-6" id="btn_link" target="_blank" href=""><i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!--end::Engage Widget 2-->
            </div>
        </div>
    </div>
    <div id="content-admin">
        <div class="p-5 col-12 col-md-12">
            <div class="card p-2 m-2">
                <div class="card-body py-3 px-10">
                    <h2>Project Progress</h2>
                    <div id="chart_admin"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php load_view('Javascript') ?>