@extends('layouts.admin')

@section('title')
    Calculate Page
@endsection

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
            <div class="container mt-2" >
                
                <div class="row mt-4 mb-4">
                        <div class="col-md-4 ">
                            <div class="card card-primary card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Employee Information</div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <form method="POST" action="{{ route('empinf')}}">
                                        @csrf 
                                            <div class=" fw-bold ">
                                                <label class="card-title h5 ">Name:</label>
                                                <input type="text" 
                                                    name="name" 
                                                    class="form-control text-primary border-primary mt-1" 
                                                    value="{{ $emp->name }}"
                                                    >
                                            </div>
                                            <div class=" fw-bold mt-3">
                                                <label class="card-title h5  ">Email:</label>
                                                <input type="email" 
                                                    name="email" 
                                                    class="form-control text-primary border-primary mt-1" 
                                                    value="{{ $emp->email }}"
                                                    >
                                            </div>
                                            <div class=" fw-bold mt-3">
                                                <label class="card-title h5  ">Phone Number:</label>
                                                <input type="text" 
                                                    name="phone_number" 
                                                    class="form-control text-primary border-primary mt-1" 
                                                    value="{{ $localNumber}}"
                                                    >
                                            </div>
                                            <div class=" fw-bold mt-4">
                                                <label class="card-title h5  ">Birth Date:</label>
                                                <input type="date" 
                                                    name="birth_date" 
                                                    class="form-control text-primary border-primary mt-1" 
                                                    value="{{ $emp->birth_date}}"
                                                    >
                                            </div>
                                            <div class=" fw-bold mt-4">
                                                <label class="card-title h5  ">Password:</label>
                                                <input type="password" 
                                                    name="password" 
                                                    class="form-control text-primary border-primary mt-1" 
                                                    value=""
                                                    >
                                                    
                                            </div>                                                                                
                                            <div class="d-flex justify-content-between  mt-4">
                                                <button type="submit" class="btn btn-primary ">
                                                    <i class="fas fa-paper-plane me-1"></i> ارسال 
                                                </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                            
                            
                            <div class="card card-round mt-4">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">إحصائيات الأسئلة</div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <canvas id="statusChart" style="max-width: 450px; max-height: 450px; "></canvas>
                                </div>
                            </div>


                        </div>



                            
                
                        <div class="col-md-8">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">إحصائيات الأسئلة</div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <canvas id="dailyChart" ></canvas>
                                </div>
                            </div>


                            <div class="card card-round mt-4">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <div class="card-title">الأسئلة الشائعة</div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <!-- Projects table -->
                                        <table class="table align-items-center mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col"> User</th>
                                                    <th scope="col" class="text-end">question</th>
                                                    <th scope="col" class="text-end">Answer</th>
                                                    <th scope="col" class="text-end"> Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($faqQuestions as $question)

                                                <tr>
                                                    <th scope="row">

                                                        {{ $question->user->name ?? 'غير متوفر' }}
                                                    </th>
                                                    <td class="text-end">{{ $question->question ?? 'غير متوفر' }}</td>
                                                    <td class="text-end">{{ $question->answer ?? 'غير متوفر' }}</td>
                                                    <td class="text-end">
                                                        @if ($question->status == 'answered')
                                                            <span class="badge bg-success">{{ $question->status }}</span>
                                                        @elseif ($question->status == 'pending')
                                                            <span class="badge bg-warning text-dark">{{ $question->status }}</span>
                                                        @elseif ($question->status == 'rejected')
                                                            <span class="badge bg-danger">{{ $question->status }}</span>                       
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div> 
            </div> 
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // مخطط الحالة
        const ctx1 = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['مجاب عليها ({{ $answered }})', 'غير مجاب ({{ $unanswered }})'],
                datasets: [{
                    data: [{{ $answered }}, {{ $unanswered }}],
                    backgroundColor: ['#4e73df', '#e74a3b'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true // تفعيل التلميحات
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true // البدء من الصفر على المحور Y
                    }
                }
            }
        });

        // مخطط الإجابات اليومية
        const ctx2 = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyAnswers->pluck('date')) !!},
                datasets: [{
                    label: 'عدد الإجابات',
                    data: {!! json_encode($dailyAnswers->pluck('count')) !!},
                    borderColor: '#1cc88a',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        rtl: true,
                        labels: {
                            font: {
                                family: 'Tahoma'
                            }
                        }
                    }
                }
            }
        });
    });
</script>