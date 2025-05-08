@extends('layouts.admin_parentLO')

@section('content')
<!-- Add this CSS style block in the head or at the top of your content section -->
<style>
    /* Dark/Gray Theme */
    .table {
        border-color: #ddd;
    }

    .table thead th {
        background-color: #333;
        color: white;
        border-color: #444;
    }

    .table tbody tr {
        background-color: #f8f9fa;
    }

    .table-bordered td,
    .table-bordered th {
        border-color: #ddd;
    }

    .btn-primary {
        background-color: #333;
        border-color: #333;
    }

    .btn-primary:hover {
        background-color: #555;
        border-color: #555;
    }

    .btn-outline-dark {
        border-color: #333;
        color: #333;
    }

    .btn-outline-dark:hover {
        background-color: #333;
        color: white;
    }

    .modal-header {
        background-color: #333;
        color: white;
    }

    .breadcrumb {
        background-color: #f8f9fa;
    }

    /* Search input styling */
    #tenant-search-input {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
</style>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Tenants</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Tenants</li>
        </ol>

        <!-- Response Modal -->
        <div class="modal fade" id="responseModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Notification</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="responseMessage"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Add this search form -->
                <form id="tenant-search-form" class="form-inline">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm"
                            style="max-width: 200px;" id="tenant-search-input"
                            placeholder="Search tenants...">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-sm"
                                style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Existing Tenants Table -->
            <div class="container">
                <table id="tenants-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Domain Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table rows will be populated dynamically -->
                    </tbody>
                </table>
            </div>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
                Add New Tenant
            </button>

            <!-- Add Tenant Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Enter Tenant Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf <!-- Add CSRF token field -->
                            <div class="form-group">
                                <label for="tenant_city">Tenant Dorm Name:</label>
                                <input type="text" class="form-control" id="tenant_city" placeholder="Enter Tenant Dorm Name">
                            </div>
                            <div class="form-group">
                                <label for="domain">Domain Name:</label>
                                <input type="text" class="form-control" id="domain" placeholder="Enter Domain Name">
                            </div>
                            <div class="form-group">
                                <label for="user_name">Name:</label>
                                <input type="text" class="form-control" id="user_name" placeholder="Enter User Name">
                            </div>
                            <div class="form-group">
                                <label for="user_email">Email:</label>
                                <input type="email" class="form-control" id="user_email" placeholder="Enter User Email">
                            </div>
                            <div class="form-group">
                                <label for="subscription_plan">Subscription Plan:</label>
                                <select class="form-control" id="subscription_plan">
                                    <option value="Basic Plan">Basic Plan</option>
                                    <option value="Standard Plan">Standard Plan</option>
                                    <option value="Premium Plan">Premium Plan</option>
                                </select>
                            </div>
                            <div class="form-group" id="subscription_details">
                                <!-- Subscription details will be dynamically added here -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="execute()">Add Tenant</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Include SweetAlert for beautiful alerts -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

            <script>
                // Custom SweetAlert theme
                const swalTheme = {
                    background: '#f8f9fa',
                    color: '#333',
                    confirmButton: {
                        background: '#333',
                        focusRing: 'rgba(0,0,0,0.2)'
                    },
                    cancelButton: {
                        background: '#6c757d',
                        color: '#fff'
                    },
                    input: {
                        background: '#fff',
                        borderColor: '#ddd'
                    }
                };

                // Modify all SweetAlert instances to use this theme
                function styledSwal(config) {
                    return Swal.fire({
                        ...config,
                        background: swalTheme.background,
                        color: swalTheme.color,
                        confirmButtonColor: swalTheme.confirmButton.background,
                        cancelButtonColor: swalTheme.cancelButton.background
                    });
                }

                // Example usage (replace all Swal.fire calls with styledSwal):
                // styledSwal({
                //     icon: 'success',
                //     title: 'Success',
                //     text: 'Operation completed successfully'
                // });
            </script>

            <script>
                // Set up AJAX headers with CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });



                function execute() {
                    const tenantCity = document.getElementById('tenant_city').value;
                    const domainName = document.getElementById('domain').value;
                    const userName = document.getElementById('user_name').value;
                    const userEmail = document.getElementById('user_email').value;
                    const subscriptionPlan = document.getElementById('subscription_plan').value;
                    const csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token

                    // Basic validation
                    if (!tenantCity || !domainName || !userName || !userEmail) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please fill in all required fields',
                        });
                        return;
                    }

                    // Show loading indicator
                    Swal.fire({
                        title: 'Processing',
                        html: 'Creating tenant...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        type: 'POST',
                        url: '/execute-tinker',
                        data: {
                            _token: csrfToken, // Include CSRF token
                            tenant_city: tenantCity,
                            domain: domainName,
                            user_name: userName,
                            user_email: userEmail,
                            subscription_plan: subscriptionPlan
                        },
                        success: function(response) {
                            $('#exampleModal').modal('hide');

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            const errorMessage = xhr.responseJSON?.message || 'An error occurred while creating the tenant';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                }

                $(document).ready(function() {
                    // Fetch tenants data when the page loads
                    fetchTenantsData();

                    // Search form handler
                    $('#tenant-search-form').on('submit', function(e) {
                        e.preventDefault();
                        const searchTerm = $('#tenant-search-input').val().toLowerCase();
                        filterTable(searchTerm);
                    });

                    // Real-time search (optional - uncomment if you want it)
                    // $('#tenant-search-input').on('keyup', function() {
                    //     const searchTerm = $(this).val().toLowerCase();
                    //     filterTable(searchTerm);
                    // });

                    function filterTable(searchTerm) {
                        $('#tenants-table tbody tr').each(function() {
                            const $row = $(this);
                            const tenantId = $row.find('td:eq(0)').text().toLowerCase();
                            const domainNames = $row.find('td:eq(1)').text().toLowerCase();

                            if (tenantId.includes(searchTerm) || domainNames.includes(searchTerm)) {
                                $row.show();
                            } else {
                                $row.hide();
                            }
                        });
                    }

                    function fetchTenantsData() {
                        $.ajax({
                            url: '/fetch-tenants',
                            type: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    populateTable(response.data);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to fetch tenants: ' + response.message
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to fetch tenants: ' + error
                                });
                            }
                        });
                    }

                    function populateTable(data) {
                        var tableBody = $('#tenants-table tbody');
                        tableBody.empty(); // Clear existing rows

                        data.forEach(function(tenant) {
                            var row = $('<tr>');
                            row.append($('<td>').text(tenant.id));

                            var domainNames = '';
                            tenant.domains.forEach(function(domain) {
                                domainNames += domain.domain + '<br>';
                            });
                            row.append($('<td>').html(domainNames));

                            // Create action buttons container
                            var actionsTd = $('<td>');

                            // Add edit button
                            var editBtn = $('<button>')
                                .text('Edit')
                                .addClass('btn btn-primary btn-sm edit-tenant mr-2')
                                .attr('data-tenant-id', tenant.id);

                            // Add delete button
                            var deleteBtn = $('<button>')
                                .text('Delete')
                                .addClass('btn btn-danger btn-sm delete-tenant')
                                .attr('data-tenant-id', tenant.id);

                            actionsTd.append(editBtn).append(deleteBtn);
                            row.append(actionsTd);

                            tableBody.append(row);
                        });
                    }
                });

                $(document).on('click', '.delete-tenant', function() {
                    var tenantId = $(this).data('tenant-id');
                    var $button = $(this); // Store reference to the button

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteSwal = Swal.fire({
                                title: 'Deleting',
                                html: 'Please wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '/delete-tenant/' + tenantId,
                                type: 'POST',
                                data: {
                                    _method: 'DELETE',
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    deleteSwal.close(); // Close the loading dialog

                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: response.message,
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            // Remove the row from the table immediately
                                            $button.closest('tr').remove();

                                            // Optional: Refresh the entire table if needed
                                            // fetchTenantsData();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message || 'Failed to delete tenant'
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    deleteSwal.close(); // Close the loading dialog
                                    const errorMessage = xhr.responseJSON?.message || 'An error occurred while deleting the tenant';
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: errorMessage
                                    });
                                }
                            });
                        }
                    });
                });

                $(document).on('click', '.edit-tenant', function() {
                    var tenantId = encodeURIComponent($(this).data('tenant-id'));

                    const editSwal = Swal.fire({
                        title: 'Loading',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/get-tenant/' + tenantId, // Now properly encoded
                        type: 'GET',
                        success: function(response) {
                            editSwal.close();
                            if (response.success) {
                                showEditModal(response.tenant);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message || 'Failed to load tenant data'
                                });
                            }
                        },
                        error: function(xhr) {
                            editSwal.close();
                            const errorMessage = xhr.responseJSON?.message || 'An error occurred while loading tenant data';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                });

                function showEditModal(tenant) {
                    Swal.fire({
                        title: 'Edit Tenant',
                        html: `
            <form id="edit-tenant-form">
                <div class="form-group">
                    <label for="tenant-id">Tenant ID</label>
                    <input type="text" class="form-control" id="tenant-id" value="${tenant.id}" readonly>
                </div>
                <div class="form-group">
                    <label for="domain-names">Domain Names (one per line)</label>
                    <textarea class="form-control" id="domain-names" rows="3">${
                        tenant.domains.map(d => d.domain).join('\n')
                    }</textarea>
                </div>
            </form>
        `,
                        showCancelButton: true,
                        confirmButtonText: 'Save Changes',
                        preConfirm: () => {
                            const domainNames = $('#domain-names').val()
                                .split('\n')
                                .map(d => d.trim())
                                .filter(d => d !== '');

                            if (domainNames.length === 0) {
                                Swal.showValidationMessage('At least one domain is required');
                                return false;
                            }

                            return {
                                domains: domainNames
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = result.value;

                            const updateSwal = Swal.fire({
                                title: 'Updating',
                                html: 'Please wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '/update-tenant/' + encodeURIComponent(tenant.id),
                                type: 'POST', // Changed to POST
                                data: {
                                    _method: 'PUT', // Added method spoofing
                                    _token: $('meta[name="csrf-token"]').attr('content'),
                                    domains: formData.domains
                                },
                                success: function(response) {
                                    updateSwal.close();

                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated!',
                                            text: response.message,
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            // Update the specific row in the table
                                            const row = $(`.edit-tenant[data-tenant-id="${tenant.id}"]`).closest('tr');
                                            // Update domains cell
                                            row.find('td:nth-child(2)').html(
                                                response.tenant.domains.map(d => d.domain).join('<br>')
                                            );
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message
                                        });
                                    }
                                },
                                error: function(xhr) {
                                    updateSwal.close();
                                    const errorMessage = xhr.responseJSON?.message || 'An error occurred while updating the tenant';
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: errorMessage
                                    });
                                }
                            });
                        }
                    });
                }
            </script>
            @endsection