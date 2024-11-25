
@extends('layouts.master')
@section('content')
    <style>
        .select {
            width: 100%; /* Make dropdowns responsive */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: white; /* Light background color */
            color: #333; /* Text color */
            transition: border-color 0.3s; /* Smooth transition for border color */
        }
        .select:focus {
            border-color: red; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }
    </style>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Leaves <span id="year"></span></h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Leaves</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave"><i class="fa fa-plus"></i> Add Leave</a>
                    </div>
                </div>
            </div>
            
            <!-- Leave Statistics -->
            <div class="row">
                @foreach($leaveInformation as $key => $leaves)
                    @if($leaves->leave_type != 'Total Leave Balance')   
                        <div class="col-md-2">
                            <div class="stats-info">
                                <h6>{{ $leaves->leave_type }}</h6>
                                <h4>{{ $leaves->leave_days }}</h4>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <!-- /Leave Statistics -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th hidden>ID</th>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th hidden>Remaining Leaves</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>No of Days</th>
                                    <th hidden>No of Days</th>
                                    <th hidden>Leave Date</th>
                                    <th hidden>Leave Day</th>
                                    <th>Reason</th>
                                    <th class="text-center">Status</th>
                                    <th>Approved by</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($getLeave as $key => $leave)
                                    @php // get photo from the table users
                                        $profiles  = DB::table('users')->where('name', $leave->approved_by)->get();
                                    @endphp
                                    <tr>
                                        <td>{{ ++$key}}</td>
                                        <td hidden class="id_record">{{ $leave->id }}</td>
                                        <td class="Employee">{{$leave->employee_name}}</td>
                                        <td class="leave_type">{{ $leave->leave_type }}</td>
                                        <td hidden class="remaining_leave">{{ $leave->remaining_leave }}</td>
                                        <td class="date_from">{{ $leave->date_from }}</td>
                                        <td class="date_to">{{ $leave->date_to }}</td>
                                        <td>{{ $leave->number_of_day }} days</td>
                                        <td hidden class="number_of_day">{{ $leave->number_of_day }}</td>
                                        <td hidden class="leave_date">{{ $leave->leave_date }}</td>
                                        <td hidden class="leave_day">{{ $leave->leave_day }}</td>
                                        <td class="reason">{{ $leave->reason }}</td>
                                        <td class="text-center">
                                            <div class="action-label">
                                                <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                    <i class="fa fa-dot-circle-o text-warning"></i> Pending
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($profiles as $key => $profile)
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar avatar-xs">
                                                        <img src="{{ URL::to('/assets/images/'.$profile->avatar) }}" alt="">
                                                    </a>
                                                    <a href="#">{{ $leave->approved_by }}</a>
                                                </h2>
                                            @endforeach
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item edit_leave" href="#" data-toggle="modal" data-target="#edit_leave"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete_leave" href="#" data-toggle="modal" data-target="#delete_approve"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
       
		<!-- Add Leave Modal -->
        <div id="add_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="applyLeave" action="{{ route('form/leaves/save') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" id="leave_type" name="leave_type">
                                            <option selected disabled>Select Leave Type</option>
                                            @foreach($leaveInformation as $key => $leaves)
                                                @if($leaves->leave_type != 'Total Leave Balance' && $leaves->leave_type != 'Use Leave' && $leaves->leave_type != 'Remaining Leave')   
                                                    <option value="{{ $leaves->leave_type }}">{{ $leaves->leave_type }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Remaining Leaves <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="remaining_leave" name="remaining_leave" readonly value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker" id="date_from" name="date_from" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker" id="date_to" name="date_to" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6" id="leave_dates_display" style="display: none"></div>
                                <div class="col-md-6" id="select_leave_day" style="display: none"></div>
                            </div>
                            <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="number_of_day" name="number_of_day" value="0" readonly>
                            </div>
                            <div class="row">
                                <div id="leave_day_select" class="col-md-12">
                                    <div class="form-group">
                                        <label>Leave Day <span class="text-danger">*</span></label>
                                        <select class="select" name="select_leave_day[]" id="leave_day">
                                            <option value="Full-Day Leave">Full-Day Leave</option>
                                            <option value="Half-Day Morning Leave">Half-Day Morning Leave</option>
                                            <option value="Half-Day Afternoon Leave">Half-Day Afternoon Leave</option>
                                            <option value="Public Holiday">Public Holiday</option>
                                            <option value="Off Schedule">Off Schedule</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea rows="2" class="form-control" name="reason"></textarea>
                            </div>
                           
                            <div class="submit-section">
                                <button type="submit" id="apply_leave" class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Add Leave Modal -->
        
        <!-- Edit Leave Modal -->
        <div id="edit_leave" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Leave</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('form/leaves/save') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" id="e_id_record" name="id_record" readonly>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Leave Type <span class="text-danger">*</span></label>
                                        <select class="select" id="e_leave_type" name="leave_type">
                                            @foreach($leaveInformation as $key => $leaves)
                                                @if($leaves->leave_type != 'Total Leave Balance' && $leaves->leave_type != 'Use Leave' && $leaves->leave_type != 'Remaining Leave')   
                                                    <option value="{{ $leaves->leave_type }}">{{ $leaves->leave_type }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Remaining Leaves <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="e_remaining_leave" name="remaining_leave" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker" id="e_date_from" name="date_from" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>To <span class="text-danger">*</span></label>
                                        <div class="cal-icon">
                                            <input type="text" class="form-control datetimepicker" id="e_date_to" name="date_to" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-md-6" id="e_leave_dates_display" style="display: block"></div>
                                <div class="col-md-6" id="e_select_leave_day" style="display: block"></div>
                            </div>
                            <div class="form-group">
                                <label>Number of days <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="e_number_of_day" name="number_of_day" readonly>
                            </div>
                            <div class="form-group">
                                <label>Leave Reason <span class="text-danger">*</span></label>
                                <textarea rows="2" class="form-control" id="e_reason" name="reason"></textarea>
                            </div>
                            <div class="submit-section">
                                <button type="submit" class="btn btn-primary submit-btn" id="e_apply_leave">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Edit Leave Modal -->
        
        <!-- Delete Leave Modal -->
        <div class="modal custom-modal fade" id="delete_approve" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-header">
                            <h3>Delete Leave</h3>
                            <p>Are you sure want to Cancel this leave?</p>
                        </div>
                        <div class="modal-btn delete-action">
                            <form action="{{ route('form/leaves/edit/delete') }}" method="POST">
                                @csrf
                                <input type="hidden" class="form-control" id="d_id_record" name="id_record" readonly>
                                <div class="row">
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary continue-btn submit-btn">Delete</button>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Leave Modal -->

    </div>
    <!-- /Page Wrapper -->
@section('script')
    <!-- Calculate Leave  -->
    <script>
        // Define the URL for the AJAX request
        var url = "{{ route('hr/get/information/leave') }}";
        
        // Function to handle leave type change
        function handleLeaveTypeChange() {
            var leaveType   = $('#leave_type').val();
            var numberOfDay = $('#number_of_day').val();    
            $.post(url, {
                leave_type: leaveType,
                number_of_day: numberOfDay,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(data) {
                if (data.response_code == 200) {
                    $('#remaining_leave').val(data.leave_type);
                }
            }, 'json');
        }
        
        function countLeaveDays()
        {
            // Get the date values from input fields
            var dateFrom = new Date($('#date_from').val());
            var dateTo   = new Date($('#date_to').val());
            var leaveDay = $('#leave_day').val();
            
            if (!isNaN(dateFrom) && !isNaN(dateTo)) {
                var numDays = Math.ceil((dateTo - dateFrom) / (1000 * 3600 * 24)) + 1;
                if (leaveDay.includes('Half-Day')) numDays -= 0.5;
                $('#number_of_day').val(numDays);
                updateRemainingLeave(numDays);

                // Clear previous display
                $('#leave_dates_display').empty();
                $('#select_leave_day').empty();

                // Display each date one by one if numDays > 0
                if (numDays > 0) {
                    for (let d = 0; d < numDays; d++) {
                        let currentDate = new Date(dateFrom);
                        currentDate.setDate(currentDate.getDate() + d);
                        var formattedDate = currentDate.getDate() + ' ' + (currentDate.getMonth() + 1) + ',' + currentDate.getFullYear();

                        document.getElementById('leave_day_select').style.display = 'block'; // or 'flex', depending on your layout
                        // Append each leave date to the display
                        if (numDays > 0) {
                            document.getElementById('leave_dates_display').style.display = 'block'; // or 'flex', depending on your layout
                            document.getElementById('select_leave_day').style.display = 'block'; // or 'flex', depending on your layout

                            const inputDate = formattedDate;
                            let [day, month, year] = inputDate.split(/[\s,]+/);
                            let date = new Date(year, month - 1, day - 1);
                            let formattedDateConvert = currentDate.getDate() + ' ' + currentDate.toLocaleString('en-GB', { month: 'short' }) + ', ' + currentDate.getFullYear();

                            // Create unique IDs for inputs and labels
                            let leaveDateInputId = `leave_date_${d}`;

                            // Append each leave date to the display
                            $('#leave_dates_display').append(`
                                <div class="form-group">
                                    <label><span class="text-danger">Leave Date ${d+1}</span></label>
                                    <div class="cal-icon">
                                        <input type="text" class="form-control" id="${leaveDateInputId}" name="leave_date[]" value="${formattedDateConvert}" readonly>
                                    </div>
                                </div>
                            `);
                            
                            // Function to generate leave day select elements
                            function generateLeaveDaySelects(numDays) {
                                $('#select_leave_day').empty(); // Clear existing elements
                                for (let d = 0; d < numDays; d++) {
                                    let leaveDayId = `leave_day_${d}`;
                                    document.getElementById('leave_day_select').style.display = 'none'; // or 'flex', depending on your layout
                                    $('#select_leave_day').append(`
                                        <div class="form-group">
                                            <label><span class="text-danger">Leave Day ${d+1}</span></label>
                                            <select class="select" name="select_leave_day[]" id="${leaveDayId}">
                                                <option value="Full-Day Leave">Full-Day Leave</option>
                                                <option value="Half-Day Morning Leave">Half-Day Morning Leave</option>
                                                <option value="Half-Day Afternoon Leave">Half-Day Afternoon Leave</option>
                                                <option value="Public Holiday">Public Holiday</option>
                                                <option value="Off Schedule">Off Schedule</option>
                                            </select>
                                        </div>
                                    `);
                                }
                            }

                            // Call this function when you need to set up the dropdowns
                            generateLeaveDaySelects(numDays);

                            // Function to update total leave days and remaining leave
                            function updateLeaveDaysAndRemaining() {
                                let totalDays = numDays; // Start with the total number of days
                                for (let d = 0; d < numDays; d++) {
                                    let leaveType = $(`#leave_day_${d}`).val(); // Get the selected leave type
                                    if (leaveType && leaveType.includes('Half-Day')) totalDays -= 0.5;
                                }
                                $('#number_of_day').val(totalDays);
                                // Update remaining leave
                                updateRemainingLeave(totalDays);
                            }

                            // Event listener for leave day selection change
                            $(document).on('change', '[id^="leave_day"]', updateLeaveDaysAndRemaining);

                            // Initial setup
                            updateLeaveDaysAndRemaining();
                        } else {
                            $('#leave_dates_display').hide();
                            $('#select_leave_day').hide();
                        }
                    }
                    
                }
            } else {
                $('#number_of_day').val('0');
                $('#leave_dates_display').text(''); // Clear the display in case of invalid dates
                $('#select_leave_day').text(''); // Clear the display in case of invalid dates
            }
        }
            
        // Function to update remaining leave
        function updateRemainingLeave(numDays) {
            $.post(url, {
                number_of_day: numDays,
                leave_type: $('#leave_type').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(data) {
                if (data.response_code == 200) {
                    $('#remaining_leave').val(data.leave_type);
                    $('#apply_leave').prop('disabled', data.leave_type < 0);
                    // Show the alert only once if leave type is less than 0
                    if (data.leave_type < 0 && !$('#apply_leave').data('alerted')) {
                        toastr.info('You cannot apply for leave at this time.');
                        $('#apply_leave').data('alerted', true);
                    } else if (numDays < 0.5) {
                        $('#apply_leave').prop('disabled', true);
                    }
                }
            }, 'json');
        }
        
        // Event listeners
        $('#leave_type').on('change', handleLeaveTypeChange);
        $('#date_from, #date_to, #leave_day').on('dp.change', countLeaveDays);

        // Clearn data in form
        $(document).on('click', '.close', function() {
            // Clear the leave dates display
            $('#leave_dates_display').empty();
            // Clear the select leave day display
            $('#select_leave_day').empty();
            // Reset other relevant fields
            $('#number_of_day').val('');
            $('#date_from').val('');
            $('#date_to').val('');
            $('#leave_type').val(''); // Reset to default value if needed
            $('#remaining_leave').val('');
            // Optionally hide any UI elements
            $('#leave_day_select').hide(); // or reset to its original state
        });
    </script>

    <!-- Validate Form  -->
    <script>
        $(document).ready(function() {
            $(".applyLeave").validate({
                rules: {
                    leave_type: {
                        required: true,
                    },
                    date_from: {
                        required: true,
                    },
                    date_to: {
                        required: true,
                    },
                    reason: {
                        required: true,
                    }
                },
                messages: {
                    leave_type: {
                        required: "Please select leave type",
                    },
                    date_from: {
                        required: "Please select date from"
                    },
                    date_to: {
                        required: "Please select date to"
                    },
                    reason: {
                        required: "Please input reason leave"
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('text-danger');
                    error.appendTo(element.parent());
                },
                submitHandler: function(form) {
                    form.submit(); // Submit the form if valid
                }
            });
        });

        $('#leave_type').on('change', function() {
            if ($(this).val()) {
                $(this).siblings('span.error').hide(); // Hide error if valid
            } else {
                $(this).siblings('span.error').show(); // Show error if invalid
            }
        });
    </script>

    <!-- Edit Leave  -->
    <script>
        $(document).on('click', '.edit_leave', function() {
            var _this = $(this).parents('tr');
            
            // Populate existing data into form fields
            $('#e_id_record').val(_this.find('.id_record').text());
            $('#e_leave_type').val(_this.find('.leave_type').text()).change();
            $('#e_remaining_leave').val(_this.find('.remaining_leave').text());
            $('#e_date_from').val(_this.find('.date_from').text());
            $('#e_date_to').val(_this.find('.date_to').text());
            $('#e_number_of_day').val(_this.find('.number_of_day').text());
            $('#e_reason').val(_this.find('.reason').text());
    
            // Function to create HTML for leave dates and leave days
            function appendLeaveData(targetSelectorDate, targetSelectorDay, leaveDateArray, leaveDayArray) {
                let htmlDateContent = '';
                let htmlDayContent = '';
                let count = 1;
    
                // Loop through both arrays simultaneously
                for (let i = 0; i < leaveDateArray.length; i++) {
                    const leaveDate = leaveDateArray[i];
                    const leaveDay = leaveDayArray[i];
    
                    // For Leave Dates
                    htmlDateContent += `
                        <div class="form-group">
                            <label><span class="text-danger">Leave Date ${count}</span></label>
                            <div class="cal-icon">
                                <input type="text" class="form-control" id="leave_date_${i}" name="leave_date[]" value="${leaveDate}" readonly>
                            </div>
                        </div>
                    `;
    
                    // For Leave Days (Select Dropdown)
                    htmlDayContent += `
                        <div class="form-group">
                            <label><span class="text-danger">Leave Day ${count}</span></label>
                            <select class="select" name="select_leave_day[]" id="e_leave_day_${i}">
                                <option value="${leaveDay}" selected>${leaveDay}</option>
                                <option value="Full-Day Leave">Full-Day Leave</option>
                                <option value="Half-Day Morning Leave">Half-Day Morning Leave</option>
                                <option value="Half-Day Afternoon Leave">Half-Day Afternoon Leave</option>
                                <option value="Public Holiday">Public Holiday</option>
                                <option value="Off Schedule">Off Schedule</option>
                            </select>
                        </div>
                    `;
                    count++;
                }
    
                // Append generated HTML to target elements
                $(targetSelectorDate).html(htmlDateContent);
                $(targetSelectorDay).html(htmlDayContent);
    
                // Attach change event listener to newly created dropdowns to recalculate total days
                $('select[name="select_leave_day[]"]').change(calculateTotalDays);
            }
    
            // Function to calculate total days
            function calculateTotalDays() {
                let totalDays = $('#e_leave_dates_display .form-group').length; // Start with the total number of days
    
                // Adjust totalDays based on the selected leave types
                $('select[name="select_leave_day[]"]').each(function() {
                    const leaveType = $(this).val();
                    if (leaveType && leaveType.includes('Half-Day')) {
                        totalDays -= 0.5;
                    }
                });
    
                // Set the calculated total days back to the input
                $('#e_number_of_day').val(totalDays);
            }
    
            // Example of parsing JSON strings (if you already have the text in JSON format)
            var leaveDateJson = _this.find('.leave_date').text();
            var leaveDayJson  = _this.find('.leave_day').text();
    
            var leaveDateArray = JSON.parse(leaveDateJson); // Parse to array
            var leaveDayArray  = JSON.parse(leaveDayJson);   // Parse to array
    
            // Clear previous displays before appending new ones
            $('#e_leave_dates_display').empty();
            $('#e_select_leave_day').empty();
    
            // Append the data to the respective sections
            appendLeaveData('#e_leave_dates_display', '#e_select_leave_day', leaveDateArray, leaveDayArray);
    
            // Initial calculation of total days
            calculateTotalDays();
        });
    </script>
    
    <!-- Edit Calculate Leave  -->
    <script>
        $(document).ready(function() {
            var url = "{{ route('hr/get/information/leave') }}";
    
            // Event listeners
            $('#e_leave_type').on('change', handleLeaveTypeChange);
            $('#e_date_from, #e_date_to').on('dp.change', countLeaveDays);
    
            // Handle leave type change
            function handleLeaveTypeChange() {
                $.post(url, {
                    leave_type: $('#e_leave_type').val(),
                    number_of_day: $('#e_number_of_day').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function(data) {
                    if (data.response_code == 200) {
                        $('#e_remaining_leave').val(data.leave_type);
                    }
                }, 'json');
            }
    
            // Count leave days based on date range
            function countLeaveDays() {
                var dateFrom = new Date($('#e_date_from').val());
                var dateTo   = new Date($('#e_date_to').val());
                if (!isNaN(dateFrom) && !isNaN(dateTo)) {
                    var numDays = Math.max(0, Math.ceil((dateTo - dateFrom) / (1000 * 3600 * 24)) + 1);
                    $('#e_number_of_day').val(numDays);
                    updateRemainingLeave(numDays);
                    displayLeaveDates(dateFrom, numDays);
                    generateLeaveDaySelections(numDays);
                } else {
                    $('#e_number_of_day').val(0);
                }
            }

            // Display leave dates
            function displayLeaveDates(dateFrom, numDays) {
                $('#e_leave_dates_display').empty();
                for (var d = 0; d < numDays; d++) {
                    var currentLeaveDate = new Date(dateFrom);
                    currentLeaveDate.setDate(dateFrom.getDate() + d);
                    var formattedDate = currentLeaveDate.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).replace(',', '');
                    $('#e_leave_dates_display').append(`
                        <div class="form-group">
                            <label><span class="text-danger">Leave Date ${d + 1}</span></label>
                            <input type="text" class="form-control" name="leave_date[]" value="${formattedDate}" readonly>
                        </div>
                    `);
                }
            }

            // Generate leave day selection dropdowns
            function generateLeaveDaySelections(numDays) {
                $('#e_select_leave_day').empty();
                for (let d = 0; d < numDays; d++) {
                    const leaveDayId = `e_leave_day_${d}`;
                    $('#e_select_leave_day').append(`
                        <div class="form-group">
                            <label><span class="text-danger">Leave Day ${d + 1}</span></label>
                            <select class="select" name="select_leave_day[]" id="${leaveDayId}">
                                <option value="Full-Day Leave">Full-Day Leave</option>
                                <option value="Half-Day Morning Leave">Half-Day Morning Leave</option>
                                <option value="Half-Day Afternoon Leave">Half-Day Afternoon Leave</option>
                                <option value="Public Holiday">Public Holiday</option>
                                <option value="Off Schedule">Off Schedule</option>
                            </select>
                        </div>
                    `);
                }

                // Recalculate totalDays whenever a dropdown changes
                $('#e_select_leave_day').on('change', '.select', function () {
                    let totalDays = numDays;

                    // Loop to adjust totalDays based on "Half-Day" selections
                    for (let d = 0; d < numDays; d++) {
                        const leaveType = $(`#e_leave_day_${d}`).val();
                        if (leaveType && leaveType.includes('Half-Day')) {
                            totalDays -= 0.5;
                        }
                    }
                    // Update display and remaining leave
                    $('#e_number_of_day').val(totalDays);
                    updateRemainingLeave(totalDays);
                });
            }

            // Update remaining leave
            function updateRemainingLeave(numDays) {
                $.post(url, {
                    number_of_day: numDays,
                    leave_type: $('#e_leave_type').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function(data) {
                    if (data.response_code == 200) {
                        $('#e_remaining_leave').val(data.leave_type);
                        $('#e_apply_leave').prop('disabled', data.leave_type < 0);
                        // Show the alert only once if leave type is less than 0
                        if (data.leave_type < 0 && !$('#e_apply_leave').data('alerted')) {
                            toastr.info('You cannot apply for leave at this time.');
                            $('#e_apply_leave').data('alerted', true);
                        } else if (numDays < 0.5) {
                            $('#e_apply_leave').prop('disabled', true);
                        }
                    }

                }, 'json');
            }
        });
    </script>

    <!-- Delete Leave  -->
    <script>
        $(document).on('click', '.delete_leave', function() {
            var _this = $(this).parents('tr');
            // Populate existing data into form fields
            $('#d_id_record').val(_this.find('.id_record').text());
        });
    </script>
       
@endsection
@endsection
