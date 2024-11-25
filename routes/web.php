<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FnfpInfoController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ----------- Public Routes -------------- //
Route::get('/', function () {
    return view('auth.login');
});

// --------- Authenticated Routes ---------- //
Route::middleware('auth')->group(function () {
    Route::get('home', function () {
        return view('home');
    });
});

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // -----------------------------login--------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    // ------------------------------ Register ---------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');    
    });

    // ----------------------------- Forget Password --------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');    
    });

    // ---------------------------- Reset Password ----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');    
    });
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // ------------------------- Main Dashboard ----------------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/home', 'index')->name('home');
            Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
        });
    });

    // --------------------------- Lock Screen ----------------------------//
    Route::controller(LockScreen::class)->group(function () {
        Route::get('lock_screen','lockScreen')->middleware('auth')->name('lock_screen');
        Route::post('unlock', 'unlock')->name('unlock');    
    });

    // --------------------------- Settings -------------------------------//
    Route::controller(SettingController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('company/settings/page', 'companySettings')->name('company/settings/page'); /** index page */
            Route::post('company/settings/save', 'saveRecord')->name('company/settings/save'); /** save record or update */
            Route::get('roles/permissions/page', 'rolesPermissions')->name('roles/permissions/page');
            Route::post('roles/permissions/save', 'addRecord')->name('roles/permissions/save');
            Route::post('roles/permissions/update', 'editRolesPermissions')->name('roles/permissions/update');
            Route::post('roles/permissions/delete', 'deleteRolesPermissions')->name('roles/permissions/delete');
            Route::get('localization/page', 'localizationIndex')->name('localization/page'); /** index page localization */
            Route::get('salary/settings/page', 'salarySettingsIndex')->name('salary/settings/page'); /** index page salary settings */
            Route::get('email/settings/page', 'emailSettingsIndex')->name('email/settings/page'); /** index page email settings */
        });
    });

    // --------------------------- Manage Users ---------------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('profile_user', 'profile')->name('profile_user');
            Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');
            Route::get('userManagement', 'index')->name('userManagement');
            Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
            Route::post('update', 'update')->name('update');
            Route::post('user/delete', 'delete')->name('user/delete');
            Route::get('change/password', 'changePasswordView')->name('change/password');
            Route::post('change/password/db', 'changePasswordDB')->name('change/password/db');
            Route::post('user/profile/emergency/contact/save', 'emergencyContactSaveOrUpdate')->name('user/profile/emergency/contact/save'); /** save or update emergency contact */
            Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
        });
    });

    // -------------------------------- Job ------------------------------//
    Route::controller(JobController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('form/job/list','jobList')->name('form/job/list');
            Route::get('form/job/view/{id}', 'jobView');
            Route::get('user/dashboard/index', 'userDashboard')->name('user/dashboard/index');    
            Route::get('jobs/dashboard/index', 'jobsDashboard')->name('jobs/dashboard/index');    
            Route::get('user/dashboard/all', 'userDashboardAll')->name('user/dashboard/all');    
            Route::get('user/dashboard/save', 'userDashboardSave')->name('user/dashboard/save');
            Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->name('user/dashboard/applied/jobs');
            Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->name('user/dashboard/interviewing');
            Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->name('user/dashboard/offered/jobs');
            Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->name('user/dashboard/visited/jobs');
            Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->name('user/dashboard/archived/jobs');
            Route::get('jobs', 'Jobs')->name('jobs');
            Route::get('job/applicants/{job_title}', 'jobApplicants');
            Route::get('job/details/{id}', 'jobDetails');
            Route::get('cv/download/{id}', 'downloadCV');
            Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
            Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
            Route::post('form/apply/job/update', 'applyJobUpdateRecord')->name('form/apply/job/update');
            Route::get('page/manage/resumes', 'manageResumesIndex')->name('page/manage/resumes');
            Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->name('page/shortlist/candidates');
            Route::get('page/interview/questions', 'interviewQuestionsIndex')->name('page/interview/questions'); // view page
            Route::post('save/category', 'categorySave')->name('save/category'); // save record category
            Route::post('save/questions', 'questionSave')->name('save/questions'); // save record questions
            Route::post('questions/update', 'questionsUpdate')->name('questions/update'); // update question
            Route::post('questions/delete', 'questionsDelete')->name('questions/delete'); // delete question
            Route::get('page/offer/approvals', 'offerApprovalsIndex')->name('page/offer/approvals');
            Route::get('page/experience/level', 'experienceLevelIndex')->name('page/experience/level');
            Route::get('page/candidates', 'candidatesIndex')->name('page/candidates');
            Route::get('page/schedule/timing', 'scheduleTimingIndex')->name('page/schedule/timing');
            Route::get('page/aptitude/result', 'aptituderesultIndex')->name('page/aptitude/result');
            Route::post('jobtypestatus/update', 'jobTypeStatusUpdate')->name('jobtypestatus/update'); // update status job type ajax
        });
    });
    
    // ------------------------- Form Employee ---------------------------//
    Route::controller(EmployeeController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            // ---------------- Employee Management Routes ---------------------
            Route::prefix('all/employee')->group(function () {
                Route::get('/card', 'cardAllEmployee')->name('all/employee/card');
                Route::get('/list', 'listAllEmployee')->name('all/employee/list');
                Route::post('/save', 'saveRecord')->name('all/employee/save');
                Route::get('/view/edit/{employee_id}', 'viewRecord');
                Route::post('/update', 'updateRecord')->name('all/employee/update');
                Route::get('/delete/{employee_id}', 'deleteRecord');
                Route::post('/search', 'employeeSearch')->name('all/employee/search');
                Route::post('/list/search', 'employeeListSearch')->name('all/employee/list/search');
            });
            Route::prefix('form')->group(function () {
                // ----------------------- Departments -------------------------
                Route::prefix('departments')->controller(EmployeeController::class)->group(function () {
                    Route::get('/page', 'index')->name('form/departments/page');    
                    Route::post('/save', 'saveRecordDepartment')->name('form/departments/save');    
                    Route::post('/update', 'updateRecordDepartment')->name('form/department/update');    
                    Route::post('/delete', 'deleteRecordDepartment')->name('form/department/delete');  
                });
                // ----------------------- Designations ------------------------
                Route::prefix('designations')->group(function () {
                    Route::get('/page', 'designationsIndex')->name('form/designations/page');    
                    Route::post('/save', 'saveRecordDesignations')->name('form/designations/save');    
                    Route::post('/update', 'updateRecordDesignations')->name('form/designations/update');    
                    Route::post('/delete', 'deleteRecordDesignations')->name('form/designations/delete');
                });
                // ------------------------- Time Sheet -----------------------
                Route::prefix('timesheet')->group(function () {
                    Route::get('/page', 'timeSheetIndex')->name('form/timesheet/page');    
                    Route::post('/save', 'saveRecordTimeSheets')->name('form/timesheet/save');    
                    Route::post('/update', 'updateRecordTimeSheets')->name('form/timesheet/update');    
                    Route::post('/delete', 'deleteRecordTimeSheets')->name('form/timesheet/delete');
                });
                // ------------------------ Over Time -------------------------
                Route::prefix('overtime')->group(function () {
                    Route::get('/page', 'overTimeIndex')->name('form/overtime/page');    
                    Route::post('/save', 'saveRecordOverTime')->name('form/overtime/save');    
                    Route::post('/update', 'updateRecordOverTime')->name('form/overtime/update');    
                    Route::post('/delete', 'deleteRecordOverTime')->name('form/overtime/delete');  
                });
            });
            // ------------------------- Profile Employee --------------------------//
            Route::get('employee/profile/{user_id}', 'profileEmployee');
        });
    });

    // ------------------------- Form Holiday ---------------------------//
    Route::controller(HolidayController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('form/holidays/new', 'holiday')->name('form/holidays/new');
            Route::post('form/holidays/save', 'saveRecord')->name('form/holidays/save');
            Route::post('form/holidays/update', 'updateRecord')->name('form/holidays/update');    
            Route::post('form/holidays/delete', 'deleteRecord')->name('form/holidays/delete');    
        });
    });

    // ---------------------------- Leaves ------------------------------//
    Route::controller(LeavesController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/leaves-admin', 'leavesAdmin')->middleware('role:admin');
            // ------------------- Employee Management Routes ---------------------
            Route::prefix('form/leaves')->group(function () {
                Route::get('/new', 'leavesAdmin')->name('form/leaves/new');
                Route::post('/save', 'saveRecordLeave')->name('form/leaves/save');
                Route::get('/employee/new', 'leavesEmployee')->name('form/leaves/employee/new');
                Route::post('/edit/delete','deleteLeave')->name('form/leaves/edit/delete');
            });
            // --------------------- Form Attendance  -------------------------//
            Route::post('get/information/leave', 'getInformationLeave')->name('hr/get/information/leave');
            Route::get('form/leavesettings/page', 'leaveSettings')->name('form/leavesettings/page');
            Route::get('attendance/page', 'attendanceIndex')->name('attendance/page');
            Route::get('attendance/employee/page', 'AttendanceEmployee')->name('attendance/employee/page');
            Route::get('form/shiftscheduling/page', 'shiftScheduLing')->name('form/shiftscheduling/page');
            Route::get('form/shiftlist/page', 'shiftList')->name('form/shiftlist/page');    
        });
    });

    // ------------------------- Form Payroll ---------------------------//
    Route::controller(PayrollController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::prefix('form/salary')->group(function () {
                Route::get('/page', 'salary')->name('form/salary/page');
                Route::post('/save','saveRecord')->name('form/salary/save');
                Route::post('/update', 'updateRecord')->name('form/salary/update');
                Route::post('/delete', 'deleteRecord')->name('form/salary/delete');
                Route::get('/view/{user_id}', 'salaryView');
            });
            Route::get('form/payroll/items', 'payrollItems')->name('form/payroll/items');    
            Route::get('extra/report/pdf', 'reportPDF');    
            Route::get('extra/report/excel', 'reportExcel');    
        });
    });    

    // ---------------------------- Reports  ----------------------------//
    Route::controller(ExpenseReportsController::class)->group(function () {
        Route::get('form/expense/reports/page', 'index')->middleware('auth')->name('form/expense/reports/page');
        Route::get('form/invoice/reports/page', 'invoiceReports')->middleware('auth')->name('form/invoice/reports/page');
        Route::get('form/daily/reports/page', 'dailyReport')->middleware('auth')->name('form/daily/reports/page');
        Route::get('form/leave/reports/page','leaveReport')->middleware('auth')->name('form/leave/reports/page');
        Route::get('form/payments/reports/page','paymentsReportIndex')->middleware('auth')->name('form/payments/reports/page');
        Route::get('form/employee/reports/page','employeeReportsIndex')->middleware('auth')->name('form/employee/reports/page');
    });

    // --------------------------- Performance  -------------------------//
    Route::controller(PerformanceController::class)->group(function () {
        Route::get('form/performance/indicator/page','index')->middleware('auth')->name('form/performance/indicator/page');
        Route::get('form/performance/page', 'performance')->middleware('auth')->name('form/performance/page');
        Route::get('form/performance/appraisal/page', 'performanceAppraisal')->middleware('auth')->name('form/performance/appraisal/page');
        Route::post('form/performance/indicator/save','saveRecordIndicator')->middleware('auth')->name('form/performance/indicator/save');
        Route::post('form/performance/indicator/delete','deleteIndicator')->middleware('auth')->name('form/performance/indicator/delete');
        Route::post('form/performance/indicator/update', 'updateIndicator')->middleware('auth')->name('form/performance/indicator/update');
        Route::post('form/performance/appraisal/save', 'saveRecordAppraisal')->middleware('auth')->name('form/performance/appraisal/save');
        Route::post('form/performance/appraisal/update', 'updateAppraisal')->middleware('auth')->name('form/performance/appraisal/update');
        Route::post('form/performance/appraisal/delete', 'deleteAppraisal')->middleware('auth')->name('form/performance/appraisal/delete');
    });

    // --------------------------- Training  ----------------------------//
    Route::controller(TrainingController::class)->group(function () {
        Route::get('form/training/list/page','index')->middleware('auth')->name('form/training/list/page');
        Route::post('form/training/save', 'addNewTraining')->middleware('auth')->name('form/training/save');
        Route::post('form/training/delete', 'deleteTraining')->middleware('auth')->name('form/training/delete');
        Route::post('form/training/update', 'updateTraining')->middleware('auth')->name('form/training/update');    
    });

    // --------------------------- Trainers  ----------------------------//
    Route::controller(TrainersController::class)->group(function () {
        Route::get('form/trainers/list/page', 'index')->middleware('auth')->name('form/trainers/list/page');
        Route::post('form/trainers/save', 'saveRecord')->middleware('auth')->name('form/trainers/save');
        Route::post('form/trainers/update', 'updateRecord')->middleware('auth')->name('form/trainers/update');
        Route::post('form/trainers/delete', 'deleteRecord')->middleware('auth')->name('form/trainers/delete');
    });

    // ------------------------- Training Type  -------------------------//
    Route::controller(TrainingTypeController::class)->group(function () {
        Route::get('form/training/type/list/page', 'index')->middleware('auth')->name('form/training/type/list/page');
        Route::post('form/training/type/save', 'saveRecord')->middleware('auth')->name('form/training/type/save');
        Route::post('form//training/type/update', 'updateRecord')->middleware('auth')->name('form//training/type/update');
        Route::post('form//training/type/delete', 'deleteTrainingType')->middleware('auth')->name('form//training/type/delete');    
    });

    // ----------------------------- Sales  ----------------------------//
    Route::controller(SalesController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            // -------------------- Estimate  --------------------//
            Route::get('form/estimates/page', 'estimatesIndex')->name('form/estimates/page');
            Route::get('create/estimate/page', 'createEstimateIndex')->name('create/estimate/page');
            Route::get('edit/estimate/{estimate_number}', 'editEstimateIndex');
            Route::get('estimate/view/{estimate_number}', 'viewEstimateIndex');

            Route::post('create/estimate/save', 'createEstimateSaveRecord')->name('create/estimate/save');
            Route::post('create/estimate/update', 'EstimateUpdateRecord')->name('create/estimate/update');
            Route::post('estimate_add/delete', 'EstimateAddDeleteRecord')->name('estimate_add/delete');
            Route::post('estimate/delete', 'EstimateDeleteRecord')->name('estimate/delete');
            // ------------------------ Payments  -------------------//
            Route::get('payments', 'Payments')->name('payments');
            Route::get('expenses/page', 'Expenses')->name('expenses/page');
            Route::post('expenses/save', 'saveRecord')->name('expenses/save');
            Route::post('expenses/update', 'updateRecord')->name('expenses/update');
            Route::post('expenses/delete', 'deleteRecord')->name('expenses/delete');
            // ---------------------- Search expenses  ---------------//
            Route::get('expenses/search', 'searchRecord')->name('expenses/search');
            Route::post('expenses/search', 'searchRecord')->name('expenses/search');
        });
    });

    // ---------------------- Personal Information ----------------------//
    Route::controller(PersonalInformationController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::post('user/information/save', 'saveRecord')->name('user/information/save');
        });
    });

    // ---------------------- Bank Information  -----------------------//
    Route::controller(BankInformationController::class)->group(function () {
        Route::middleware('auth')->group(function () {
            Route::post('bank/information/save', 'saveRecord')->name('bank/information/save');
        });
    });

    // ---------------------- Document  -----------------------//
    Route::middleware(['auth'])->group(function () {
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });


   // ---------------------- Fnfp Information  -----------------------//
Route::get('/fnfp', [FnfpInfoController::class, 'index'])->name('fnfp.index');
Route::post('/fnfp', [FnfpInfoController::class, 'store'])->name('fnfp.store');



});
