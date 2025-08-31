@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-semibold mb-1">Notifications</h1>
            <p class="text-muted mb-0">Your recent alerts and updates</p>
        </div>
        <button class="btn btn-sm btn-soft-primary" id="mark-all-read">
            <i class="bi bi-check2-all me-1"></i> Mark All Read
        </button>
    </div>

    <div class="card border-0 rounded-3 shadow-sm overflow-hidden">
        <div class="card-header bg-transparent border-bottom">
            <div class="d-flex align-items-center">
                <div class="icon-shape bg-primary-soft text-primary rounded-circle me-3">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <h5 class="mb-0">Notification Center</h5>
                    <small class="text-muted">{{ $notifications->total() }} total notifications</small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $notification)
                <a href="{{ route('notification.redirect', ['nid' => $notification->id]) }}" class="list-group-item list-group-item-action border-0 py-3 px-4 notification-item
                    {{ $notification->read_at ? '' : 'bg-primary-soft' }}"
                    data-notification-id="{{ $notification->id }}">
                    <div class="d-flex align-items-start">
                        <div class="me-3 position-relative">
                            <div
                                class="icon-shape bg-{{ $notification->data['color'] ?? 'primary' }}-soft text-{{ $notification->data['color'] ?? 'primary' }} rounded-circle">
                                <i class="bi bi-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                            </div>
                            @unless($notification->read_at)
                            <span
                                class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">New alert</span>
                            </span>
                            @endunless
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="mb-1 fw-semibold">{{ $notification->data['title'] ?? 'New Notification' }}
                                </h6>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 text-muted">{{ $notification->data['message'] ?? '' }}</p>
                            @if(isset($notification->data['context']))
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">{{ $notification->data['context'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="list-group-item text-center py-5 border-0">
                    <div class="icon-shape bg-light-soft text-muted rounded-circle mx-auto mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-bell-slash" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-semibold">No notifications yet</h5>
                    <p class="text-muted mb-0">We'll notify you when something arrives</p>
                </div>
                @endforelse
            </div>
        </div>

        @if($notifications->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern color scheme */
    :root {
        --primary: #6366f1;
        --primary-soft: rgba(99, 102, 241, 0.08);
        --secondary: #8b5cf6;
        --success: #10b981;
        --info: #06b6d4;
        --warning: #f59e0b;
        --danger: #ef4444;
        --light: #f8fafc;
        --dark: #1e293b;
    }

    .bg-primary-soft {
        background-color: var(--primary-soft);
    }

    .bg-success-soft {
        background-color: rgba(16, 185, 129, 0.08);
    }

    .bg-info-soft {
        background-color: rgba(6, 182, 212, 0.08);
    }

    .bg-warning-soft {
        background-color: rgba(245, 158, 11, 0.08);
    }

    .bg-danger-soft {
        background-color: rgba(239, 68, 68, 0.08);
    }

    .bg-light-soft {
        background-color: rgba(248, 250, 252, 0.5);
    }

    /* Icon shapes */
    .icon-shape {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Notification items */
    .notification-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .notification-item:hover {
        background-color: rgba(99, 102, 241, 0.05) !important;
        border-left-color: var(--primary);
        transform: translateX(2px);
    }

    /* Pagination styling */
    .pagination .page-item.active .page-link {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .pagination .page-link {
        color: var(--primary);
    }

    /* Button styling */
    .btn-soft-primary {
        background-color: var(--primary-soft);
        color: var(--primary);
        border: none;
    }

    .btn-soft-primary:hover {
        background-color: var(--primary);
        color: white;
    }

    /* Card styling */
    .card {
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }

    /* Typography */
    body {
        color: #334155;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        color: #1e293b;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Modern toastr configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "timeOut": 5000,
            "extendedTimeOut": 2000,
            "hideDuration": 300,
            "showDuration": 300,
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false,
            "iconClass": "toast-info",
            "containerId": "toast-container",
            "newestOnTop": true,
            "preventDuplicates": true,
            "closeHtml": "<button><i class='bi bi-x-lg'></i></button>"
        };

        // Mark notification as read when clicked
        $(document).on('click', '.notification-item', function(e) {
            e.preventDefault();
            const notificationId = $(this).data('notification-id');
            const link = $(this).attr('href');
            const $notificationItem = $(this);

            // Only mark as read if unread
            if ($notificationItem.hasClass('bg-primary-soft')) {
                $.ajax({
                    url: `/notifications/${notificationId}/mark-as-read`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        // Update UI immediately
                        $notificationItem.removeClass('bg-primary-soft');
                        $notificationItem.find('.position-absolute').remove();

                        // Update unread count in navbar
                        updateUnreadCount();

                        // Navigate to link
                        window.location.href = link;
                    },
                    error: function() {
                        window.location.href = link;
                    }
                });
            } else {
                window.location.href = link;
            }
        });

        // Mark all as read
        $('#mark-all-read').click(function() {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...');

            $.ajax({
                url: '/notifications/mark-all-as-read',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    // Update all notifications UI
                    $('.notification-item').each(function() {
                        $(this).removeClass('bg-primary-soft');
                        $(this).find('.position-absolute').remove();
                    });

                    // Update unread count in navbar
                    updateUnreadCount();

                    toastr.success('All notifications marked as read');
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i class="bi bi-check2-all me-1"></i> Mark All Read');
                }
            });
        });

        // Function to update unread count in navbar
        function updateUnreadCount() {
            $.get('/notifications/unread-count', function(data) {
                const $badge = $('#notification-badge');
                if (data.count > 0) {
                    $badge.text(data.count).removeClass('d-none');
                } else {
                    $badge.addClass('d-none');
                }
            });
        }

        // Show toast for new notifications
        @if(session('new_notification'))
            toastr.info(
                '{{ session('new_notification')['message'] }}',
                '{{ session('new_notification')['title'] }}'
            );
        @endif
    });
</script>
@endpush
