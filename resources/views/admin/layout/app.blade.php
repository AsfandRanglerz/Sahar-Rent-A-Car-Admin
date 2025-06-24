<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Sahar Rent A Car</title>
    <!-- Developed By Ranglerz -->
    <link rel="stylesheet"
        href="https://www.ranglerz.com/cost-to-make-a-web-ios-or-android-app-and-how-long-does-it-take.php">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/toastr/toastr.css') }}">
    

    <link rel="stylesheet"
    href="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/datatables.min.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/img/Sahar_logo.png') }}' />
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
</head>

<body>
    <div class="loader"></div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('admin.common.header')
            @include('admin.common.side_menu')
            @yield('content')
            @include('admin.common.footer')
        </div>
    </div>
    <!-- Add this BEFORE your script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function updatependingRequestCounter() {
              $.ajax({
                  url: "{{ route('pending.counter') }}",
                  type: 'GET',
                  success: function(response) {
                       // Ensure response.count exists and handle counts over 99
                      let count = response.count || 0; // Default to 0 if no count is returned
                      $('#pendingRequestCounter').text(count > 10 ? '10+' : count);
                      // $('#orderCounter').text(response.count);
                  },
                  error: function(xhr, status, error) {
                      console.log(error);
                  }
              });
          }
          updatependingRequestCounter();
          setInterval(updatependingRequestCounter, 10000);
  </script>

<script>
    function updateDropoffCounter() {
        $.ajax({
            url: "{{ route('dropoff.counter') }}",
            type: 'GET',
            success: function(response) {
                let count = response.count || 0;
                $('#dropoffCounter').text(count > 10 ? '10+' : count);
            },
            error: function(xhr, status, error) {
                console.error("Dropoff Counter Error:", error);
            }
        });
    }

    // Run once on page load
    updateDropoffCounter();

    // Update every 10 seconds
    setInterval(updateDropoffCounter, 10000);
</script>

<script>
    function updatelicenseCounter() {
        $.ajax({
            url: "{{ route('license.counter') }}",
            type: 'GET',
            success: function(response) {
                let count = response.count || 0;
                $('#updatelicenseCounter').text(count > 10 ? '10+' : count);
            },
            error: function(xhr, status, error) {
                console.error("License Approval Counter Error:", error);
            }
        });
    }

    // Run once on page load
    updatelicenseCounter();

    // Update every 10 seconds
    setInterval(updatelicenseCounter, 10000);
</script>

<script>
    function updateActiveBookingCounter() {
        $.ajax({
            url: "{{ route('booking.activeCount') }}",
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let count = response.count || 0;
                $('#bookingCounter').text(count > 10 ? '10+' : count);
            },
            error: function(xhr, status, error) {
                console.error("Booking Counter Error:", error);
            }
        });
    }

    // Run once on page load
    $(document).ready(function () {
        updateActiveBookingCounter();
        setInterval(updateActiveBookingCounter, 10000); // Update every 10 seconds
    });
</script>

<script>

    function updatechatCounter() {

        $.ajax({

            url: "{{ route('chat.counter') }}",

            type: 'GET',

            dataType: 'json',

            success: function(response) {

                let count = response.count || 0;

                $('#chatRequestCounter').text(count > 10 ? '10+' : count);

            },

            error: function(xhr, status, error) {

                console.error("chat Counter Error:", error);

            }

        });

    }



    // Run once on page load

    $(document).ready(function () {

        updatechatCounter();

        setInterval(updatechatCounter, 10000); // Update every 10 seconds

    });

</script>


    <!-- General JS Scripts -->
    <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('public/admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('public/admin/assets/js/page/index.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('public/admin/assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>
    <script src="{{ asset('public/admin/toastr/toastr.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    

      {{-- DataTbales --}}
      <script src="{{ asset('public/admin/assets/bundles/datatables/datatables.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
      </script>
      <script src="{{ asset('public/admin/assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/page/datatables.js') }}"></script>

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{-- <script>
        /*toastr popup function*/
        function toastrPopUp() {
            toastr.options = {
                "closeButton": true,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "3000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        }

        /*toastr popup function*/
        toastrPopUp();
    </script> --}}

    <script>
        toastr.options = {
            "closeButton": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000"
        };

        @if (session('message'))
            toastr.success("{{ session('message') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    @yield('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
