@extends('tenantviews.tenantlayout.homelayout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .attraction-section {
        background-color: #f7f7f7;
        padding: 60px 0;
    }

    .attraction-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        background-color: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .attraction-card:hover {
        transform: scale(1.05);
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
    }

    .attraction-image {
        height: 300px;
        object-fit: cover;
        border-bottom: 2px solid #ddd;
    }

    .attraction-card-body {
        padding: 30px;
    }

    .attraction-card-body h3 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-top: 0;
    }

    .attraction-card-body p {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .attraction-card-body .icon {
        font-size: 24px;
        margin-right: 10px;
        color: #337ab7;
    }

    .attraction-card-body .location {
        color: #555;
    }

    .attraction-card-body .price {
        font-weight: bold;
        color: #d9534f;
    }

    /* New styles for the order button and modal */
    .order-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        display: block;
        margin: 20px auto 0;
        transition: background-color 0.3s;
    }

    .order-btn:hover {
        background-color: #218838;
    }

    .modal-header {
        background-color: #28a745;
        color: white;
    }

    .modal-title {
        font-weight: bold;
    }

    .close {
        color: white;
        opacity: 1;
    }
</style>

<section class="page-section attraction-section" id="home">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-heading text-uppercase">Room Options in {{ $tenantName }} Dormitory</h2>
            <h6 class="text-muted">
                Explore the comfortable and welcoming rooms of {{ $tenantName }} Dormitory, where each space is designed to provide a relaxing and convenient stay.
                Whether you're looking for a quiet retreat to study or a cozy place to unwind,
                discover the perfect dorm experience that meets your needs and preferences.
            </h6>
        </div>

        <div class="row">
            @foreach($touristSpots as $touristSpot)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card attraction-card">
                    <img src="/storage/visitor/image/{{ $touristSpot->image }}" class="card-img-top attraction-image" alt="{{ $touristSpot->name }}">
                    <div class="card-body attraction-card-body">
                        <h3 class="card-title text-center">{{ $touristSpot->name }}</h3>
                        <p class="card-text text-center">
                            <i class="fas fa-tag icon"></i>
                            <span class="location">{{ $touristSpot->location }}</span>
                        </p>
                        <p style="text-align: center;" class="card-text">Description: {{ $touristSpot->description }}</p>
                        <p class="card-text" style="text-align: center;">
                            <i class="fas fa-money-bill-alt icon"></i>
                            <span class="price">Monthly Price: ₱{{ number_format($touristSpot->entry_fee, 2) }}</span>
                        </p>

                        <!-- Order Button -->
                        <button type="button" class="order-btn"
                            style="background-color:rgb(41, 154, 253) !important; color: white !important; border: none !important;"
                            data-bs-toggle="modal"
                            data-bs-target="#orderModal"
                            data-spot-id="{{ $touristSpot->id }}"
                            data-spot-name="{{ $touristSpot->name }}"
                            data-spot-price="{{ $touristSpot->entry_fee }}"
                            data-spot-subscription="{{ $touristSpot->subscriptionType }}">
                            >
                            Book Now
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header bg-gray-800 border-gray-700">
                <h5 class="modal-title text-white" id="orderModalLabel">Book Your Room</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" id="modalCloseButton">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-gray-900">
                <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <input type="hidden" id="spotId" name="touristspot_id">
                    <input type="hidden" id="actualPrice" name="total_price">

                    <div class="form-group">
                        <label for="spotName" class="text-gray-300">Room:</label>
                        <input type="text" class="form-control bg-gray-800 border-gray-700 text-white" id="spotName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="spotPrice" class="text-gray-300">Monthly Price:</label>
                        <input type="text" class="form-control bg-gray-800 border-gray-700 text-white" id="spotPrice" readonly>
                    </div>
                    <div class="form-group">
                        <label for="customerName" class="text-gray-300">Your Name:</label>
                        <input type="text" class="form-control bg-gray-800 border-gray-700 text-white" id="customerName" name="name" required placeholder="Enter your full name">
                    </div>
                    <div class="form-group">
                        <label for="customerPhone" class="text-gray-300">Phone Number:</label>
                        <input type="tel" class="form-control bg-gray-800 border-gray-700 text-white" id="customerPhone" name="phone" required placeholder="Enter your phone number">
                    </div>
                    <div class="form-group">
                        <label for="orderType" class="text-gray-300">Room Type:</label>
                        <select class="form-control bg-gray-800 border-gray-700 text-white" id="orderType" name="order_type" required>
                            <option value="Immediate">Immediate</option>
                            <option value="Reservation">Reservation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="subscriptionType" class="text-gray-300">Subscription Type:</label>
                        <select class="form-control bg-gray-800 border-gray-700 text-white" id="subscriptionType" name="subscriptionType" required>
                            <option value="Silver">Silver - First Semester Only</option>
                            <option value="Gold">Gold - Whole Semester</option>
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer bg-gray-800 border-gray-700">
                <button type="button" class="btn btn-outline-light" data-dismiss="modal" id="cancelButton">Cancel</button>
                <button type="button" class="btn btn-primary bg-blue-600 hover:bg-blue-700" id="submitOrder">Submit</button>
            </div>
        </div>
    </div>
</div>


<style>
    /* Custom dark theme styles */
    .bg-gray-800 {
        background-color: #2d3748;
    }

    .bg-gray-900 {
        background-color: #1a202c;
    }

    .border-gray-700 {
        border-color: #4a5568;
    }

    .text-gray-300 {
        color: #e2e8f0;
    }

    .bg-dark {
        background-color: #1a202c;
    }

    .modal-content {
        border: 1px solid #4a5568;
    }

    .form-control {
        background-color: #2d3748;
        border: 1px solid #4a5568;
        color: white;
    }

    .form-control:focus {
        background-color: #2d3748;
        color: white;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    .btn-outline-light {
        color: #f7fafc;
        border-color: #f7fafc;
    }

    .btn-outline-light:hover {
        color: #1a202c;
        background-color: #f7fafc;
    }

    .bg-blue-600 {
        background-color: #3182ce;
    }

    .hover\:bg-blue-700:hover {
        background-color: #2c5282;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize modal
        var orderModal = new bootstrap.Modal(document.getElementById('orderModal'));

        // Close modal when X button is clicked
        $('#modalCloseButton').click(function() {
            orderModal.hide();
        });

        // Close modal when Cancel button is clicked
        $('#cancelButton').click(function() {
            orderModal.hide();
        });

        // When the order button is clicked, populate the modal with the spot data
        $('.order-btn').click(function() {
            var spotId = $(this).data('spot-id');
            var spotName = $(this).data('spot-name');
            var spotPrice = parseFloat($(this).data('spot-price'));
            

            $('#spotId').val(spotId);
            $('#spotName').val(spotName);
            $('#spotPrice').val('₱' + spotPrice.toFixed(2));
            // Set the actual price value in the hidden field
            $('#actualPrice').val(spotPrice.toFixed(2));
        });

        // Submit order form with SweetAlert confirmation
        $('#submitOrder').click(function(e) {
            e.preventDefault();

            // Validate form
            if ($('#orderForm')[0].checkValidity()) {
                Swal.fire({
                    title: 'Confirm Booking',
                    text: 'Are you sure you want to place this booking?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, place booking!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        $('#orderForm').submit();
                    }
                });
            } else {
                // If form is invalid, show validation messages
                $('#orderForm')[0].reportValidity();
            }
        });

        // Handle form submission response
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('
            success ') }}',
            icon: 'success',
            confirmButtonColor: '#28a745'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('
            error ') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
        @endif
    });
</script>

@if(session()->has('success') || session()->has('error'))
<script>
    $(document).ready(function() {
        @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('
            success ') }}',
            icon: 'success',
            confirmButtonColor: '#28a745'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: '{{ session('
            error ') }}',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
        @endif
    });
</script>
@endif
@endsection