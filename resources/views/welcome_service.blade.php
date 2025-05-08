<!-- Services-->
<section class="page-section" id="services">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase">Unidorm Services</h2>
            <h3 class="section-subheading text-muted">Find Your Perfect Dorm, Easy and Fast</h3>
            <!-- Add a new description about the service -->
            <p class="text-muted">
            Unidorm provides a hassle-free platform for discovering and managing dormitories. Whether you're a student searching for the perfect place to stay or a dorm owner managing multiple listings, Unidorm gives you the tools you need to make dorm hunting and management easier.
            </p>
        </div>
        <div class="row text-center">
    <!-- Basic Plan -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="my-2">Basic Plan</h4>
            </div>
            <div class="card-body">
                <h5>$19.99/month</h5>
                <ul class="list-unstyled">
                    <li>List up to 10 dorms</li>
                </ul>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#basicPlanModal">
                    Subscribe
                </button>
            </div>
        </div>
    </div>

    <!-- Standard Plan -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="my-2">Standard Plan</h4>
            </div>
            <div class="card-body">
                <h5>$49.99/month</h5>
                <ul class="list-unstyled">
                    <li>List up to 50 dorms</li>
                </ul>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#standardPlanModal">
                    Subscribe
                </button>
            </div>
        </div>
    </div>

    <!-- Premium Plan -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="my-2">Premium Plan</h4>
            </div>
            <div class="card-body">
                <h5>$99.99/month</h5>
                <ul class="list-unstyled">
                    <li>List up unlimited dorms</li>
                </ul>
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#premiumPlanModal">
                    Subscribe
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Basic Plan Modal -->
<div class="modal fade" id="basicPlanModal" tabindex="-1" aria-labelledby="basicPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="basicPlanModalLabel">Basic Plan Subscription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="basicPlanForm" action="{{ route('subscribe') }}" method="POST">
          @csrf
          <input type="hidden" name="plan_type" value="basic">
          <div class="mb-3">
            <label for="basicName" class="form-label">Name</label>
            <input type="text" class="form-control" id="basicName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="basicEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="basicEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="basicCity" class="form-label">Dorm</label>
            <input type="text" class="form-control" id="basicCity" name="city" required>
          </div>
          <div class="mb-3">
            <label for="basicPaymentMethod" class="form-label">Payment Method</label>
            <select class="form-select" id="basicPaymentMethod" name="payment_method" required>
              <option value="">Select Payment Method</option>
              <option value="credit">Credit Card</option>
              <option value="debit">Debit Card</option>
              <option value="paypal">PayPal</option>
              <option value="other">Other</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Standard Plan Modal -->
<div class="modal fade" id="standardPlanModal" tabindex="-1" aria-labelledby="standardPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="standardPlanModalLabel">Standard Plan Subscription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="standardPlanForm" action="{{ route('subscribe') }}" method="POST">
          @csrf
          <input type="hidden" name="plan_type" value="standard">
          <div class="mb-3">
            <label for="standardName" class="form-label">Name</label>
            <input type="text" class="form-control" id="standardName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="standardEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="standardEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="standardCity" class="form-label">Dorm</label>
            <input type="text" class="form-control" id="standardCity" name="city" required>
          </div>
          <div class="mb-3">
            <label for="standardPaymentMethod" class="form-label">Payment Method</label>
            <select class="form-select" id="standardPaymentMethod" name="payment_method" required>
              <option value="">Select Payment Method</option>
              <option value="credit">Credit Card</option>
              <option value="debit">Debit Card</option>
              <option value="paypal">PayPal</option>
              <option value="other">Other</option>
            </select>
          </div>
          <button type="submit" class="btn btn-success">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Premium Plan Modal -->
<div class="modal fade" id="premiumPlanModal" tabindex="-1" aria-labelledby="premiumPlanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="premiumPlanModalLabel">Premium Plan Subscription</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="premiumPlanForm" action="{{ route('subscribe') }}" method="POST">
          @csrf
          <input type="hidden" name="plan_type" value="premium">
          <div class="mb-3">
            <label for="premiumName" class="form-label">Name</label>
            <input type="text" class="form-control" id="premiumName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="premiumEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="premiumEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="premiumCity" class="form-label">Dorm</label>
            <input type="text" class="form-control" id="premiumCity" name="city" required>
          </div>
          <div class="mb-3">
            <label for="premiumPaymentMethod" class="form-label">Payment Method</label>
            <select class="form-select" id="premiumPaymentMethod" name="payment_method" required>
              <option value="">Select Payment Method</option>
              <option value="credit">Credit Card</option>
              <option value="debit">Debit Card</option>
              <option value="paypal">PayPal</option>
              <option value="other">Gcash</option>
            </select>
          </div>
          <button type="submit" class="btn btn-warning">Subscribe</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add this at the top of your file -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Services Section (keep your existing HTML) -->
<section class="page-section" id="services">
    <!-- Your existing services content -->
</section>

<!-- Modal Forms (keep your existing HTML) -->
<div class="modal fade" id="basicPlanModal" tabindex="-1" aria-labelledby="basicPlanModalLabel" aria-hidden="true">
  <!-- Your existing basic plan modal content -->
</div>

<div class="modal fade" id="standardPlanModal" tabindex="-1" aria-labelledby="standardPlanModalLabel" aria-hidden="true">
  <!-- Your existing standard plan modal content -->
</div>

<div class="modal fade" id="premiumPlanModal" tabindex="-1" aria-labelledby="premiumPlanModalLabel" aria-hidden="true">
  <!-- Your existing premium plan modal content -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    // Handle form submission for all plans
    $("[id$='PlanForm']").submit(function (event) {
        event.preventDefault();
        const form = $(this);
        const planType = form.find('input[name="plan_type"]').val();
        const formData = form.serialize();

        // Show loading indicator
        Swal.fire({
            title: 'Processing Subscription',
            html: 'Please wait while we set up your account...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: formData,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Subscription Successful!',
                    text: `You are now subscribed to the ${planType} plan.`,
                    confirmButtonText: 'Okay'
                }).then(() => {
                    form[0].reset();
                    form.closest('.modal').modal('hide');
                });
            },
            error: function (xhr) {
                let errorMessage = 'An error occurred during subscription';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Subscription Failed',
                    html: errorMessage,
                    confirmButtonText: 'Try Again'
                });
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
