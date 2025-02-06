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
            <div class="row mt-4">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <!-- الأيقونة -->
                                <div class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #3B82F6, #6366F1);
                                    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.15);
                                ">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                
                                <!-- النص -->
                                <div class="numbers">
                                    <p class="card-category mb-1">Users</p>
                                    <h4 class="card-title mb-0">{{$usersCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <!-- الأيقونة -->
                                <div class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #3B82F6, #6366F1);
                                    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.15);
                                ">
                                    <i class="bi bi-building-fill text-white"></i>
                                </div>
                                
                                <!-- النص -->
                                <div class="numbers">
                                    <p class="card-category mb-1">Universities</p>
                                    <h4 class="card-title mb-0">{{$universitiesCount}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div
                        class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #3B82F6, #6366F1);
                                    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.15);"
                        >
                          <i class="fas fa-luggage-cart text-white"></i>
                        </div>
                        <div class="numbers">
                          <p class="card-category mb-1">Profit</p>
                          <h4 class="card-title  mb-0">$ {{$totalPayments}}</h4>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                    <div class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #3B82F6, #6366F1);
                                    box-shadow: 0 4px 6px rgba(59, 130, 246, 0.15);">
                                    <i class="bi bi-journals text-white"></i>
                                    </div>
                                    <div class="numbers">
                                        <p class="card-category mb-1">Specializations</p>
                                        <h4 class="card-title  mb-0">{{$specializationsCount}}</h4>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">عدد الطلبات حسب الاختصاص والجامعة</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="specializationChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Comparison date</div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <form method="POST" action="{{ route('date')}}">
                                @csrf 
                                    <div class=" fw-bold ">
                                        <label class="card-title h5 ">Start Date:</label>
                                        <input type="date" 
                                            name="startdate" 
                                            class="form-control text-primary border-primary mt-1" 
                                            value="{{ $startdate }}"
                                            required>
                                    </div>
                                    <div class=" fw-bold mt-4">
                                    <label class="card-title h5  ">End Date:</label>
                                        <input type="date" 
                                            name="enddate" 
                                            class="form-control text-primary border-primary mt-1" 
                                            value="{{ $enddate }}"
                                            required>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between  mt-4">
                                        <button type="submit" class="btn btn-primary ">
                                            <i class="fas fa-paper-plane me-1"></i> تحديث القيمة
                                        </button>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <div class="card card-round card-primary mt-3" style="    transform: translateY(-5px);
                        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);" >
                        <div class="card-body pb-0">
                            @if ($result === 1)                                   
                                <div class=" alert alert-success text-white" >التاريخ الحالي يسمح بان تحسب المفاضلة ✅  </div>
                                <button type="submit" class="btn btn-danger mb-2  w-100" data-bs-toggle="modal" data-bs-target="#exampleModal12">
                                    <i class="bi bi-calculator "></i> Calculate
                                </button>                                 
                            @elseif ($result === 0)
                                <div class="alert alert-warning mb-4 mt-3">التاريخ الحالي خلال فترة المفاضلة أو قبلها ⏳</div>
                            @else
                                <div class="alert alert-danger mb-4 mt-3">لم يتم تعيين التواريخ بعد ❌</div>
                            @endif
                        </div>
                    </div>
                </div>                
            </div>

            <div class="row mt-4">
             <!-- Accepted Request (أخضر) -->
                <div class="col-sm-4 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #4CAF50, #2E7D32);
                                    box-shadow: 0 4px 6px rgba(76, 175, 80, 0.15);">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                                <div class="numbers">
                                    <p class="card-category mb-1">Accepted Request</p>
                                    <h4 class="card-title mb-0">{{$acceptedRequests}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Request (برتقالي) -->
                <div class="col-sm-4 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #FFA726, #FB8C00);
                                    box-shadow: 0 4px 6px rgba(255, 167, 38, 0.15);">
                                    <i class="bi bi-person-fill-gear text-white"></i>
                                </div>
                                <div class="numbers">
                                    <p class="card-category mb-1">Pending Request</p>
                                    <h4 class="card-title mb-0">{{$pendingRequests}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rejected Request (أحمر) -->
                <div class="col-sm-4 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-horizontal me-3" style="
                                    background: linear-gradient(45deg, #EF5350, #D32F2F);
                                    box-shadow: 0 4px 6px rgba(239, 83, 80, 0.15);">
                                    <i class="bi bi-person-fill-exclamation text-white"></i>
                                </div>
                                <div class="numbers">
                                    <p class="card-category mb-1">Rejected Request</p>
                                    <h4 class="card-title mb-0">{{$rejectedRequests}}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4 mb-4 ">
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">إحصائيات الطلبات</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="requestsChart" style="max-width: 450px; max-height: 450px; "></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                     
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Latest requests</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">اسم المستخدم</th>
                                        <th scope="col" class="text-end">الجامعة</th>
                                        <th scope="col" class="text-end">التخصص</th>
                                        <th scope="col" class="text-end">نوع الطلب</th>
                                        <th scope="col" class="text-end"> المجموع</th>
                                        <th scope="col" class="text-end"> الحالة</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($latestRequests as $request)

                                    <tr>
                                        <th scope="row">
                                            <button
                                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                                            >
                                                <i class="fa fa-check"></i>
                                            </button>
                                            {{ $request->payment->user->name ?? 'غير متوفر' }}
                                        </th>
                                        <td class="text-end">{{ $request->specializationPerUniversity->university->name ?? 'غير متوفر' }}</td>
                                        <td class="text-end">{{ $request->specializationPerUniversity->specialization->name ?? 'غير متوفر' }}</td>
                                        <td class="text-end">{{ $request->r_type->type }}</td>
                                        <td class="text-end">{{ $request->total }}</td>
                                        <td class="text-end">
                                            @if ($request->request_status == 'Accepted')
                                                <span class="badge bg-success">{{ $request->request_status }}</span>
                                            @elseif ($request->request_status == 'pending')
                                                <span class="badge bg-warning text-dark">{{ $request->request_status }}</span>
                                            @elseif ($request->request_status == 'rejected')
                                                <span class="badge bg-danger">{{ $request->request_status }}</span>                       
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

    <div class="modal fade" id="exampleModal12" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" >
                    <h1 class="modal-title fs-5" id="exampleModalLabel">تأكيد القرار</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" dir="rtl">
                    هل أنت متأكد من أنك تريد بداية حساب المفاضلة؟
                </div>
                <div class="modal-footer">
                    <form action="{{ route('calculate') }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger" id="sign-out-button">تأكيد</button>
                    </form>
                </div>   
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('specializationChart').getContext('2d');

        // بيانات الجامعات والتخصصات
        var requestData = @json($requestsData);
        var universities = [...new Set(requestData.map(item => item.university_name))];

        var specializations = [...new Set(requestData.map(item => item.specialization_name))];

        var dataset = specializations.map(spec => {
            return {
                label: spec,
                data: universities.map(uni => {
                    var found = requestData.find(item => item.university_name === uni && item.specialization_name === spec);
                    return found ? found.total_requests : 0;
                }),
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`
            };
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: universities,
                datasets: dataset
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'عدد الطلبات لكل اختصاص في كل جامعة'
                    }
                },
                scales: {
                    x: { stacked: true },
                    y: { stacked: true }
                }
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('requestsChart').getContext('2d');
        const requestsChart = new Chart(ctx, {
            type: 'pie', // نوع المخطط (عمودي)
            data: {
                labels: ['Accepted', 'Pending', 'Rejected'], // التصنيفات
                datasets: [{
                    label: 'Requests',
                    data: [{{ $acceptedRequests }}, {{ $pendingRequests }}, {{ $rejectedRequests }}], // البيانات
                    backgroundColor: [
                        'rgba(76, 175, 80, 0.7)', // أخضر (مقبولة)
                        'rgba(255, 167, 38, 0.7)', // برتقالي (معلقة)
                        'rgba(239, 83, 80, 0.7)'  // أحمر (مرفوضة)
                    ],
                    borderColor: [
                        'rgba(76, 175, 80, 1)', // أخضر (مقبولة)
                        'rgba(255, 167, 38, 1)', // برتقالي (معلقة)
                        'rgba(239, 83, 80, 1)'  // أحمر (مرفوضة)
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
        
    });
</script>



