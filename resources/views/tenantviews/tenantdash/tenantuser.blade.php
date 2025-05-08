@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-users me-2"></i>User Management</h3>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-1"></i> Add User
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Search Input -->
            <div class="mb-3">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control" id="userSearchInput" 
                           placeholder="Search users...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach($users as $user)
                        <tr class="user-row" 
                            data-name="{{ strtolower($user->name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role="{{ strtolower($user->role) }}">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'info' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-user"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete-user"
                                    data-id="{{ $user->id }}">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addUserModalLabel"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editUserModalLabel"><i class="fas fa-user-edit me-2"></i>Edit User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="PUT">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_user_id" name="id">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select name="role" id="edit_role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    $(document).ready(function() {
        // Client-side search functionality
        $('#userSearchInput').on('input', function() {
            const searchValue = $(this).val().toLowerCase().trim();
            
            if (searchValue === '') {
                $('.user-row').show();
                return;
            }
            
            $('.user-row').each(function() {
                const name = $(this).data('name');
                const email = $(this).data('email');
                const role = $(this).data('role');
                
                if (name.includes(searchValue) || 
                    email.includes(searchValue) || 
                    role.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Show "No results" message if all rows are hidden
            if ($('.user-row:visible').length === 0) {
                if ($('#no-results-row').length === 0) {
                    $('#usersTableBody').append('<tr id="no-results-row"><td colspan="5" class="text-center py-4 text-muted">No matching users found</td></tr>');
                }
            } else {
                $('#no-results-row').remove();
            }
        });

        // Handle add form submission
        $('#addUserForm').on('submit', function(e) {
            e.preventDefault();
            submitUserForm($(this), "{{ route('tenant.register') }}", 'User added successfully');
        });

        // Handle edit button click
        $(document).on('click', '.edit-user', function() {
            const userId = $(this).data('id');
            const userName = $(this).data('name');
            const userEmail = $(this).data('email');
            const userRole = $(this).data('role');

            $('#edit_user_id').val(userId);
            $('#edit_username').val(userName);
            $('#edit_email').val(userEmail);
            $('#edit_role').val(userRole);

            $('#editUserModal').modal('show');
        });

        // Handle edit form submission
        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            const userId = $('#edit_user_id').val();
            submitUserForm(
                $(this),
                `/tenant/user/${userId}`,
                'User updated successfully'
            );
        });

        // Handle delete button click
        $(document).on('click', '.delete-user', function() {
            const userId = $(this).data('id');

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
                    $.ajax({
                        url: `/tenant/user/${userId}`,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message || 'User has been deleted.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.message || 'Failed to delete user.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        // Generic function to handle form submission
        function submitUserForm(form, url, successMessage) {
            Swal.fire({
                title: 'Processing',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: url,
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    form.closest('.modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || successMessage,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        form[0].reset();
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage
                    });
                }
            });
        }

        // Reset forms when modals are closed
        $('#addUserModal, #editUserModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
        });
    });
</script>

<style>
    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table th {
        font-weight: 600;
    }

    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    #userSearchInput {
        border-radius: 0.25rem 0 0 0.25rem;
    }
    
    .input-group-append .input-group-text {
        border-radius: 0 0.25rem 0.25rem 0;
    }
</style>
@endsection