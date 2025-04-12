<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar sticky">
    <div class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i
                        data-feather="align-justify"></i></a></li>
            <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                    <i data-feather="maximize"></i>
                </a></li>
        </ul>
    </div>
    <ul class="navbar-nav navbar-right">
       
        <?php
         // use App\Models\Admin;
        // use App\Models\Subadmin;
        // $admin = Auth::guard('admin')->check() ? Admin::find(Auth::guard('admin')->id()) : null;
        // $Subadmin = Auth::guard('subadmin')->check() ? Subadmin::find(Auth::guard('subadmin')->id()) : null;
        use App\Models\Admin;
        use App\Models\Subadmin;
        
        // Get logged-in admin or subadmin
        $admin = Auth::guard('admin')->user();
        $subadmin = Auth::guard('subadmin')->user();
        
        // Determine user and profile image
        $user = $admin ?? $subadmin;
        $image = $user && $user->image ? asset($user->image) : asset('public/admin/assets/img/user.png');
        ?>
        <li class="dropdown">
            <a href="#" class="nav-link" data-toggle="dropdown">
                <i data-feather="bell"></i>
                <span id="notificationCounter" class="badge badge-primary" style="padding: 6px 9px">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div id="notificationList" style="max-height: 300px; overflow-y: auto;">
                    <p class="dropdown-item">No new notifications</p>
                </div>
                <a href="#" id="markAllRead" class="dropdown-footer text-center" style="margin-left:58px; font-size:11px;">
                    Mark all as read
                </a>
            </div>
        </li>
        
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                {{-- @if ($admin && $admin->image) --}}
                    <img alt="image" src="{{ $image }}" class="user-img-radious-style">
                {{-- @else
                    <img alt="image" src="{{ asset('public/admin/assets/img/user.png') }}"
                        class="user-img-radious-style">
                @endif --}}
                {{-- <img
                    alt="image" src="{{ asset('public/admin/assets/img/user.png') }}" class="user-img-radious-style"> --}}
                <span class="d-sm-none d-lg-inline-block"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right pullDown">
                <div class="dropdown-title">Hello {{ $admin->name ?? 'Admin' }}</div>
                <a href="{{ url('admin/profile') }}" class="dropdown-item has-icon"> <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('admin/logout') }}" class="dropdown-item has-icon text-danger"> <i
                        class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    function fetchNotifications() {
        $.ajax({
            url: "{{ route('admin.notifications') }}",
            type: 'GET',
            success: function(response) {
                let notifications = response.notifications;
                let notificationCounter = $('#notificationCounter');
                let notificationList = $('#notificationList');

                if (notifications.length > 0) {
                    notificationCounter.text(notifications.length);
                    notificationList.html('');

                    notifications.forEach(notification => {
                        notificationList.append(`
                            <a href="#" class="dropdown-item" style="text-wrap:wrap; font:menu;" data-id="${notification.id}">
                                <span>${notification.message}</span>
                                <br>
                                <small class="text-muted">${new Date(notification.created_at).toLocaleString()}</small>
                            </a>
                        `);
                    });
                } else {
                    notificationCounter.text(0);
                    notificationList.html('<p class="dropdown-item">No new notifications</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Notification Fetch Error:", error);
            }
        });
    }

    // Run once on page load
    fetchNotifications();

    // Refresh every 10 seconds
    setInterval(fetchNotifications, 10000);
</script>

<script>
    $('#notificationList').on('click', 'a', function () {
        let notificationId = $(this).data('id'); // Get the notification ID
        $.ajax({
            url: "{{ route('admin.notifications.mark-read') }}",
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { id: notificationId },
            success: function(response) {
                fetchNotifications(); // Refresh notification list
            }
        });
    });

    $('#markAllRead').on('click', function () {
    $.ajax({
        url: "{{ route('admin.notifications.mark-all-read') }}",
        type: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function(response) {
            fetchNotifications(); // Refresh notification list
        }
    });
});
</script>

@endsection