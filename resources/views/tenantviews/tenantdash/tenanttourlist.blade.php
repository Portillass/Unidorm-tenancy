@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<!-- Main Content -->
<div class="col-md-12">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <div class="d-flex justify-content-between aligns-center">
                <h5 class="mb-0">Room Management</h5>
            </div>
        </div>

        <!-- Additional Styling -->
        <style>
            .card-header {
                background-color: #333;
                /* Dark grey background */
                color: white;
                /* White text */
            }
        </style>

        <div class="card-body">
            <!-- Search Form -->
            <form method="GET" action="{{ route('tenantlist') }}" class="mb-4">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control form-control-sm" name="search" placeholder="Search Room..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Rooms Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Image</th>
                            <th>Room/Floor</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Monthly Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($touristSpots as $touristSpot)
                        <tr>
                            <td>
                                <img src="/storage/visitor/image/{{ $touristSpot->image }}"
                                    alt="Room Image"
                                    class="img-thumbnail"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </td>
                            <td class="align-middle">{{ $touristSpot->name }}</td>
                            <td class="align-middle">{{ $touristSpot->location }}</td>
                            <td class="align-middle">{{ Str::limit($touristSpot->description, 50) }}</td>
                            <td class="align-middle">₱{{ number_format($touristSpot->entry_fee, 2) }}</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-outline-primary edit-tourist-spot"
                                    data-id="{{ $touristSpot->id }}"
                                    data-toggle="modal"
                                    data-target="#editModal-{{ $touristSpot->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-tourist-spot"
                                    data-id="{{ $touristSpot->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-dorm fa-2x mb-2"></i>
                                    <p>No Rooms found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addTouristSpotModal">
    <i class="fas fa-plus"></i> Add Room
</button>

<!-- Add Room Modal -->
<div class="modal fade" id="addTouristSpotModal" tabindex="-1" role="dialog" aria-labelledby="addTouristSpotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="addTouristSpotModalLabel">
                    <i class="fas fa-plus"></i> Add Room
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addForm" method="POST" action="{{ route('touristspot.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" Name>Room/Floor</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Category</label>
                        <select class="form-control" name="location" id="location" required>
                            <option value="Single Rooms">Single Rooms</option>
                            <option value="Shared Rooms">Shared Rooms</option>
                            <option value="Studio Apartments">Studio Apartments</option>
                            <option value="Luxury Dorms">Luxury Dorms</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="entry_fee">Monthly Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="number" class="form-control" id="entry_fee" name="entry_fee" step="0.01" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@foreach ($touristSpots as $touristSpot)
<div class="modal fade" id="editModal-{{ $touristSpot->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-{{ $touristSpot->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="editModalLabel-{{ $touristSpot->id }}">
                    <i class="fas fa-edit"></i> Edit Room
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-form-{{ $touristSpot->id }}" method="POST" action="{{ route('touristspot.update', ['touristSpot' => $touristSpot->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Room/Floor</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $touristSpot->name }}" required>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="location">Category</label>
                            <select class="form-control" name="location" id="location" required>
                                <option value="Single Rooms" {{ $touristSpot->location == 'Single Rooms' ? 'selected' : '' }}>Single Rooms</option>
                                <option value="Shared Rooms" {{ $touristSpot->location == 'Shared Rooms' ? 'selected' : '' }}>Shared Rooms</option>
                                <option value="Studio Apartments" {{ $touristSpot->location == 'Studio Apartments' ? 'selected' : '' }}>Studio Apartments</option>
                                <option value="Luxury Dorms" {{ $touristSpot->location == 'Luxury Dorms' ? 'selected' : '' }}>Luxury Dorms</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $touristSpot->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="entry_fee">Monthly Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₱</span>
                            </div>
                            <input type="number" class="form-control" id="entry_fee" name="entry_fee" value="{{ $touristSpot->entry_fee }}" step="0.01" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Include SweetAlert and other scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize file input labels
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Handle form submissions with SweetAlert
        $('#addForm').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            Swal.fire({
                title: 'Adding Room',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Room added successfully',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'An error occurred while adding the Room';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        });

        // Delete Room handling
        $('.delete-tourist-spot').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const $row = $(this).closest('tr');

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
                    Swal.fire({
                        title: 'Deleting',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: '/touristspot/' + id + '/delete',
                        type: 'GET',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Room deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                $row.fadeOut(400, function() {
                                    $(this).remove();
                                });
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'An error occurred while deleting the Room'
                            });
                        }
                    });
                }
            });
        });

        // Edit form submissions
        $('[id^="edit-form-"]').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const id = $(form).attr('id').split('-')[2];

            Swal.fire({
                title: 'Updating Room',
                html: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Room updated successfully',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'An error occurred while updating the Room';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>

<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .table thead th {
        border-top: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
    }

    .img-thumbnail {
        padding: 0;
        border: 1px solid #dee2e6;
    }

    .btn-outline-primary {
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: white;
    }

    .btn-outline-danger {
        transition: all 0.3s;
    }

    .btn-outline-danger:hover {
        background-color: #e74a3b;
        color: white;
    }

    .modal-header {
        border-radius: 0;
        border: none;
    }

    .custom-file-label::after {
        content: "Browse";
    }
</style>
@endsection