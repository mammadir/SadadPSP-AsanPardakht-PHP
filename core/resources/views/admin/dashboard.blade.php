@extends('fp::layouts.admin')

@section('page-title'){{ lang('lang.dashboard') }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ lang('lang.income') }}
                    <div class="btn-group float-left btn-group-sm">
                        <button type="button" class="btn @if(site_config('live_stats')) btn-success @else btn-secondary @endif dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ lang('lang.live_stats') }}: @if(site_config('live_stats')) {{ lang('lang.active') }} @else {{ lang('lang.deactive') }} @endif
                        </button>
                        <div class="dropdown-menu">
                            @if(site_config('live_stats'))
                                <a class="dropdown-item" href="{{ route('admin-dashboard-live-toggle') }}">{{ lang('lang.deactivate') }}</a>
                            @else
                                <a class="dropdown-item" href="{{ route('admin-dashboard-live-toggle') }}">{{ lang('lang.activate') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="income-chart" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ lang('lang.transactions_count') }}
                    <div class="btn-group float-left btn-group-sm">
                        <button type="button" class="btn @if(site_config('live_stats')) btn-success @else btn-secondary @endif dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ lang('lang.live_stats') }}: @if(site_config('live_stats')) {{ lang('lang.active') }} @else {{ lang('lang.deactive') }} @endif
                        </button>
                        <div class="dropdown-menu">
                            @if(site_config('live_stats'))
                                <a class="dropdown-item" href="{{ route('admin-dashboard-live-toggle') }}">{{ lang('lang.deactivate') }}</a>
                            @else
                                <a class="dropdown-item" href="{{ route('admin-dashboard-live-toggle') }}">{{ lang('lang.activate') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="transactions-chart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/chartjs/Chart.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/chartjs/Chart.min.js') }}"></script>
    <script>
      getData();

      @if(site_config('live_stats'))
      setInterval(function () {
        getData();
      }, 3000);

      @endif

      function getData() {
        $.get("{{ route('admin-dashboard-live') }}").then(function (data) {
          updateIncomeChart(data.statistics);
          updateTransactionsChart(data.statistics);
        });
      }

      let incomeChartEl = document.getElementById('income-chart');
      let incomeChart;
      let transactionsChartEl = document.getElementById('transactions-chart');
      let transactionsChart;

      function updateIncomeChart(data) {
        let labels = [];
        let values = [];
        for (let i = 0; i < data.length; i++) {
          labels.push(data[i].date);
          values.push(data[i].income);
        }

        if (incomeChart) {
          incomeChart.data.labels = labels;
          incomeChart.data.datasets[0].data = values;
          incomeChart.update();
        } else {
          incomeChart = new Chart(incomeChartEl, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: "{{ lang('lang.income') }}",
                data: values,
                backgroundColor: [
                  'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                  'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        }
      }

      function updateTransactionsChart(data) {
        let labels = [];
        let values = [];
        for (let i = 0; i < data.length; i++) {
          labels.push(data[i].date);
          values.push(data[i].count);
        }

        if (transactionsChart) {
          transactionsChart.data.labels = labels;
          transactionsChart.data.datasets[0].data = values;
          transactionsChart.update();
        } else {
          transactionsChart = new Chart(transactionsChartEl, {
            type: 'line',
            data: {
              labels: labels,
              datasets: [{
                label: "{{ lang('lang.transactions_count') }}",
                data: values,
                backgroundColor: [
                  'rgba(54, 162, 235, 0.2)',
                ],
                borderColor: [
                  'rgba(54, 162, 235, 1)',
                ],
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          });
        }
      }
    </script>
@endpush
