@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<div class="col-md-12">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Tracking Dashboard</h5>
            </div>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <div class="mb-4">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control form-control-sm" id="searchInput" 
                           placeholder="Search customer/order..." autocomplete="off">
                    <div class="input-group-append">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>

            <h5 class="card-title">Welcome, {{ $tenantName }}!</h5>
            <p class="card-text text-muted">Track and manage all customer orders</p>

            <!-- Orders Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Image</th>
                            <th>Room/Floor</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        @forelse($customers as $customer)
                        <tr class="order-row" 
                            data-customer="{{ strtolower($customer->name) }}" 
                            data-item="{{ strtolower($customer->touristspot->name ?? '') }}">
                            <td>
                                <img src="/storage/visitor/image/{{ $customer->touristspot->image ?? 'default.jpg' }}"
                                     alt="Item Image" class="img-thumbnail order-img">
                            </td>
                            <td class="align-middle">{{ $customer->touristspot->name ?? 'N/A' }}</td>
                            <td class="align-middle">{{ $customer->name }}</td>
                            <td class="align-middle">{{ $customer->phone ?? 'N/A' }}</td>
                            <!-- <td class="align-middle">₱{{ number_format($customer->total_price, 2) }}</td> -->
                            <td class="align-middle">
                                <button class="btn btn-sm btn-outline-primary view-orders" data-name="{{ $customer->name }}">
                                    <i class="fas fa-history"></i> History
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-4 text-muted">No orders found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Order History Modal -->
<div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-history"></i> Order History for <span id="customerName"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Room/Floor</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th>Monthly Price</th>
                                <th>Subscription Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="modalHistoryBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    let currentCustomerData = [];
    let searchTimer;

    // Client-side search functionality
    $('#searchInput').on('input', function() {
        clearTimeout(searchTimer);
        
        searchTimer = setTimeout(function() {
            const searchValue = $('#searchInput').val().toLowerCase().trim();
            
            if (searchValue === '') {
                $('.order-row').show();
                return;
            }
            
            $('.order-row').each(function() {
                const customerName = $(this).data('customer');
                const itemName = $(this).data('item');
                
                if (customerName.includes(searchValue) || itemName.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Show "No results" message if all rows are hidden
            if ($('.order-row:visible').length === 0) {
                $('#ordersTableBody').append('<tr id="no-results-row"><td colspan="8" class="text-center py-4 text-muted">No matching orders found</td></tr>');
            } else {
                $('#no-results-row').remove();
            }
        }, 300);
    });

    // Show Modal & Load Orders
    $(document).on('click', '.view-orders', function () {
        const name = $(this).data('name');
        $('#customerName').text(name);
        $('#modalHistoryBody').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
        $('#orderHistoryModal').modal('show');

        $.get(`/orders/customer/${encodeURIComponent(name)}`, function (data) {
            currentCustomerData = data;
            renderModalTable(data);
        }).fail(() => {
            $('#modalHistoryBody').html('<tr><td colspan="8" class="text-danger text-center">Failed to load history</td></tr>');
        });
    });

    // Status update in modal
    $(document).on('click', '.update-status', function(e) {
        e.preventDefault();
        const newStatus = $(this).data('status');
        const orderId = $(this).data('id');
        const orderRow = $(this).closest('tr');
        
        Swal.fire({
            title: 'Update Status',
            text: `Are you sure you want to mark this booking as ${newStatus}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/orders/${orderId}/status`,
                    method: 'PATCH',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: newStatus
                    },
                    success: function(response) {
                        orderRow.find('.badge')
                            .removeClass('badge-warning badge-info badge-success badge-danger')
                            .addClass('badge-' + getStatusBadgeClass(newStatus))
                            .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                        
                        Swal.fire(
                            'Updated!',
                            'Booking status has been updated.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Failed to update status.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Render Orders in modal
    function renderModalTable(data) {
        const body = $('#modalHistoryBody');
        body.empty();

        if (!data.length) {
            body.append('<tr><td colspan="8" class="text-center text-muted">No order history found</td></tr>');
            return;
        }

        data.forEach(order => {
            const date = new Date(order.created_at).toLocaleString('en-US');
            const item = order.touristspot?.name || 'N/A';
            const image = order.touristspot?.image || 'default.jpg';
            body.append(`
                <tr>
                    <td>${date}</td>
                    <td>${item}</td>
                    <td><img src="/storage/visitor/image/${image}" class="img-thumbnail" style="width: 60px;"></td>
                    <td>${order.order_type}</td>
                    <td>₱${parseFloat(order.total_price).toFixed(2)}</td>
                    <td>${order.subscriptionType}</td>
                    <td>
                        <span class="badge badge-${getStatusBadgeClass(order.status)}">
                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item update-status" href="#" data-id="${order.id}" data-status="pending">Mark as Pending</a>
                                <a class="dropdown-item update-status" href="#" data-id="${order.id}" data-status="confirmed">Mark as Confirmed</a>
                                <a class="dropdown-item update-status" href="#" data-id="${order.id}" data-status="cancelled">Mark as Cancelled</a>
                            </div>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    // Modal Close Triggers
    $('#orderHistoryModal .close, #orderHistoryModal .btn-secondary').on('click', function () {
        $('#orderHistoryModal').modal('hide');
    });

    // Helper function to get badge class based on status
    function getStatusBadgeClass(status) {
        switch(status) {
            case 'pending': return 'warning';
            case 'confirmed': return 'info';
            case 'cancelled': return 'danger';
            default: return 'secondary';
        }
    }
});
</script>

<style>
    .order-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }

    .modal-lg {
        max-width: 850px;
    }

    .card-body {
        background-color: #f8f9fa;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: white;
    }
    
    .badge-warning {
        background-color: #f6c23e;
    }
    
    .badge-info {
        background-color: #36b9cc;
    }
    
    .badge-success {
        background-color: #1cc88a;
    }
    
    .badge-danger {
        background-color: #e74a3b;
    }
    
    .dropdown-menu {
        min-width: 10rem;
    }
    
    .dropdown-item {
        padding: 0.25rem 1.5rem;
    }
</style>
@endsection

@php
function getStatusBadgeClass($status) {
    switch($status) {
        case 'pending': return 'warning';
        case 'confirmed': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
}
@endphp