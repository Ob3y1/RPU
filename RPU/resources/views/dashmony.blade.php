@extends('layouts.admin')

@section('title')
    Dashboard Page
@endsection
<style>
.icon-horizontal {
    padding: 10px;
    border-radius: 5px;
    transition: transform 0.3s ease;
    min-width: 40px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-horizontal i {
    font-size: 1.5rem;
}

.icon-horizontal:hover {
    transform: scale(1.05);
}

</style>

@section('content')
        <nav class="navbar bg-body-tertiary ">
                <div class="container-fluid  justify-content-md-between">
                    <a class="navbar-brand fw-normal fs-4 mb-1 mb-md-0 d-none d-sm-block ">Dashboard</a>
                </div>
        </nav>
    <div class="container ">
        <div class="page-inner">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <div class="row mt-4 ">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">نمو المدفوعات حسب التاريخ</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container" style="min-height: 375px">
                                 <canvas id="paymentsGrowthChart" ></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-round" >
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">إحصائيات المدفوعات</div>
                            </div>
                        </div>
                        <div class="card-body mt-2" >
                            <canvas id="paymentPieChart" ></canvas>
                        </div>
                    </div>
                </div>
            </div>  



            <div class="row mt-4">
                
                <!-- المدفوعات الناجحة -->
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">المدفوعات الناجحة</h5>
                            <p class="card-text">{{ $successfulPayments }} </p>
                        </div>
                    </div>
                </div>

                <!-- المدفوعات المعلقة -->
                <div class="col-md-4">
                    <div class="card text-white  " style="
                            background: linear-gradient(45deg, #FFA726, #FB8C00);
                            box-shadow: 0 4px 6px rgba(255, 167, 38, 0.15);
                        ">
                        <div class="card-body">
                            <h5 class="card-title">المدفوعات المعلقة</h5>
                            <p class="card-text">{{ $pendingPayments }}</p>
                        </div>
                    </div>
                </div>

                <!-- المدفوعات الفاشلة -->
                <div class="col-md-4">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">المدفوعات الفاشلة</h5>
                            <p class="card-text">{{ $failedPayments }}</p>
                        </div>
                    </div>
                </div>

                
            </div>
            

            <div class="row mb-4 mt-4">
                <div class="col-md-4">
                    

                    <!-- الربح الكامل -->
                    <div class="col-md-12 mb-5">
                        <div class="card text-white" style="background: linear-gradient(45deg, #9FA8DA, #7986CB);
                            box-shadow: 0 4px 6px rgba(159, 168, 218, 0.15);">
                            <div class="card-body">
                                <h5 class="card-title">الربح الكامل</h5>
                                <p class="card-text">{{ $totalPayments }} $</p>
                            </div>
                        </div>
                    </div>

                    <!-- المدفوعات المستخدمة -->
                    <div class="col-md-12 mb-5">
                        <div class="card text-white" style="
                            background: linear-gradient(45deg, #3B82F6, #6366F1);
                            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.15);
                        ">
                            <div class="card-body">
                                <h5 class="card-title">المدفوعات المستخدمة</h5>
                                <p class="card-text">{{ $usedPayments }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card card-round" style="    transform: translateY(-5px);
                        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);" >
                        <form method="POST" action="{{ route('fee')}}">
                            @csrf <!-- إذا كنت تستخدم Laravel -->
                            <div class="card-body pb-0">
                                <div class="h1 fw-bold float-end">
                                    <input type="text" 
                                        name="fee" 
                                        class="form-control text-primary border-primary" 
                                        style="width: 90px; font-size: 2.5rem"
                                        step="1"
                                        value="{{ $requestFee }}$"
                                        required>
                                </div>
                                
                                <h5 class="card-title text-white">Request Fee</h5>
                                
                                <div class="d-flex justify-content-between  mt-4">
                                    <button type="submit" class="btn btn-primary ">
                                        <i class="fas fa-paper-plane me-1"></i> تحديث القيمة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                    
                <div class="col-md-8 " >
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">إحصائيات المدفوعات</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="paymentsChart"></canvas>
                        </div>
                    </div>
                </div>
                
            </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('paymentsGrowthChart').getContext('2d');
            var paymentsGrowthChart = new Chart(ctx, {
            type: 'line', // مخطط خطي
            data: {
                labels: {!! json_encode($dates) !!}, // تواريخ المدفوعات
                datasets: [{
                    label: 'مجموع المدفوعات',
                    data: {!! json_encode($totals) !!}, // إجمالي المدفوعات لكل يوم
                    borderColor: 'blue',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3 // سلاسة الخط
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'التاريخ'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'إجمالي المدفوعات ($)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx = document.getElementById('paymentsChart').getContext('2d');
        var paymentsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['إجمالي المدفوعات', 'ناجحة', 'معلقة', 'فاشلة', 'مستخدمة'],
                datasets: [{
                    label: 'إحصائيات المدفوعات',
                    data: [
                        {{ $totalPayments1 }},
                        {{ $successfulPayments }},
                        {{ $pendingPayments }},
                        {{ $failedPayments }},
                        {{ $usedPayments }}
                    ],
                    backgroundColor: [
                        'blue', 'green', 'orange', 'red', 'purple'
                    ]
                }]
            }
        });
        var ctx = document.getElementById('paymentPieChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: @json($labels), // أسماء طرق الدفع
                datasets: [{
                    label: 'Payment Methods',
                    data: @json($data), // عدد المدفوعات لكل طريقة
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' payments';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
