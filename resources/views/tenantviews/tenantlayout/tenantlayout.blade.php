<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $tenantName }} Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="Tenant/css/navbar1.css">
  <link rel="stylesheet" href="Tenant/css/sidebar1.css">
  <link rel="stylesheet" href="Tenant/css/maincontent.css">
  <style>
    body.dark-mode {
      background-color: #121212;
      color: #ffffff;
    }

    .navbar-dark-mode {
      background-color: #333333;
    }

    .sidebar-dark-mode {
      background-color: #333333;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav id="navbar" class="navbar navbar-expand-lg navbar-dark py-4">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand" href="#">
        <img id="navbarLogo" src="Tenant/resource/logo-main.png" alt="Logo" width="125" height="50" class="d-inline-block align-text-top"> {{ $tenantName }} Dashboard
      </a>

      <!-- Toggler button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar right side -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
       
        <!-- <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button> -->
       
        <!-- Dropdown menu for profile -->
        <div class="dropdown">
          <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg" alt="Profile" class="rounded-circle" width="30" height="30">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item" href="{{ route('tenant.profile') }}">Profile</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#customizeModal">Customize</a></li>
            <li><a class="dropdown-item" href="{{ route('tenantlogout') }}">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <aside class="col-md-2 sidebar">
        <div class="list-group">
          <a href="tenantdashboard" class="list-group-item list-group-item-action text-white">Dashboard</a>

          @auth
        <!-- Manage Menu - only for admin -->
        @if(auth()->user()->role === 'admin')
        <a href="tenantbhlist" class="list-group-item list-group-item-action text-white">Manage Dorm</a>
        @endif

        <!-- Track Order - visible to all authenticated users -->
        <a href="tenanttrackorder" class="list-group-item list-group-item-action text-white">Track Bookings</a>

        <!-- View Users - only for admin -->
        @if(auth()->user()->role === 'admin')
        <a href="tenantusers" class="list-group-item list-group-item-action text-white">View Users</a>
        @endif
    @endauth
    
    <!-- Subscription details -->
          <hr class="list-group-divider text-white"><span class="text-white fas fa-lightbulb"> Subscription</span></hr>
          <div class="list-group-item text-white" id="subscriptionDetails">
            <!-- Subscription data will be populated dynamically -->
          </div>
          <!-- Add more sidebar links as needed -->
        </div>
      </aside>
      <!-- Main Content -->
      <main class="col-md-9">
        <div class="container">
          @yield('content')
        </div>
      </main>
    </div>
  </div>

  <!-- Add User Modal
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addUserForm">
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
              <select name="role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add User</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> -->

  <!-- Include SweetAlert JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      // Handle form submission
      $('#addUserForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading alert
        Swal.fire({
          title: 'Processing',
          html: 'Adding new user...',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Get form data
        const formData = $(this).serialize();

        // AJAX request
        $.ajax({
          url: "{{ route('tenant.register') }}",
          type: "POST",
          data: formData,
          success: function(response) {
            // Close the modal
            $('#addUserModal').modal('hide');

            // Show success message
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: response.message || 'User added successfully',
              timer: 3000,
              showConfirmButton: false
            }).then(() => {
              // Reset form
              $('#addUserForm')[0].reset();

              // Reload page or update user list as needed
              window.location.reload();
            });
          },
          error: function(xhr) {
            let errorMessage = 'An error occurred while adding the user';

            // Try to get server error message
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
              // Handle validation errors
              errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }

            Swal.fire({
              icon: 'error',
              title: 'Error',
              html: errorMessage
            });
          }
        });
      });

      // Reset form when modal is closed
      $('#addUserModal').on('hidden.bs.modal', function() {
        $('#addUserForm')[0].reset();
      });
    });
  </script>

  <style>
    .btn-close-white {
      filter: invert(1) grayscale(100%) brightness(200%);
    }
  </style>

  <!-- Customize Modal -->
  <div class="modal fade" id="customizeModal" tabindex="-1" aria-labelledby="customizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customizeModalLabel">Customize Dashboard</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="navbarColor" class="form-label">Navbar Color</label>
            <input type="color" class="form-control form-control-color" id="navbarColor" value="#000000">
          </div>
          <div class="mb-3">
            <label for="sidebarColor" class="form-label">Sidebar Color</label>
            <input type="color" class="form-control form-control-color" id="sidebarColor" value="#000000">
          </div>
          <div class="mb-3">
            <label for="textColor" class="form-label">Text Color</label>
            <input type="color" class="form-control form-control-color" id="textColor" value="#000000">
          </div>
          <div class="mb-3">
            <label for="fontSize" class="form-label">Font Size</label>
            <input type="range" class="form-range" id="fontSize" min="12" max="24" step="1" value="16">
          </div>
          <div class="mb-3">
            <label for="darkModeToggle" class="form-label">Dark Mode</label>
            <input type="checkbox" class="form-check-input" id="darkModeToggle">
          </div>
          <div class="mb-3">
            <label for="logoUpload" class="form-label">Upload Logo</label>
            <input type="file" class="form-control" id="logoUpload" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JavaScript to handle real-time customization -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const navbarColorInput = document.getElementById('navbarColor');
      const sidebarColorInput = document.getElementById('sidebarColor');
      const textColorInput = document.getElementById('textColor');
      const fontSizeInput = document.getElementById('fontSize');
      const darkModeToggle = document.getElementById('darkModeToggle');
      const logoUploadInput = document.getElementById('logoUpload');

      // Load saved customizations
      const navbarColor = localStorage.getItem('navbarColor') || '#000000';
      const sidebarColor = localStorage.getItem('sidebarColor') || '#000000';
      const textColor = localStorage.getItem('textColor') || '#000000';
      const fontSize = localStorage.getItem('fontSize') || '16';
      const darkMode = localStorage.getItem('darkMode') === 'true';
      const logoUrl = localStorage.getItem('logoUrl') || 'Tenant/resource/logo-main.png';

      // Apply saved customizations
      updateUI(navbarColor, sidebarColor, textColor, fontSize, darkMode, logoUrl);

      // Set input values to saved customizations
      navbarColorInput.value = navbarColor;
      sidebarColorInput.value = sidebarColor;
      textColorInput.value = textColor;
      fontSizeInput.value = fontSize;
      darkModeToggle.checked = darkMode;

      // Handle immediate changes in customization modal
      navbarColorInput.addEventListener('input', function() {
        updateUI(navbarColorInput.value, sidebarColorInput.value, textColorInput.value, fontSizeInput.value, darkModeToggle.checked, logoUrl);
      });

      sidebarColorInput.addEventListener('input', function() {
        updateUI(navbarColorInput.value, sidebarColorInput.value, textColorInput.value, fontSizeInput.value, darkModeToggle.checked, logoUrl);
      });

      textColorInput.addEventListener('input', function() {
        updateUI(navbarColorInput.value, sidebarColorInput.value, textColorInput.value, fontSizeInput.value, darkModeToggle.checked, logoUrl);
      });

      fontSizeInput.addEventListener('input', function() {
        updateUI(navbarColorInput.value, sidebarColorInput.value, textColorInput.value, fontSizeInput.value, darkModeToggle.checked, logoUrl);
      });

      darkModeToggle.addEventListener('change', function() {
        updateUI(navbarColorInput.value, sidebarColorInput.value, textColorInput.value, fontSizeInput.value, darkModeToggle.checked, logoUrl);
      });

      // Handle Save Changes button click
      document.getElementById('saveChanges').addEventListener('click', function() {
        const navbarColor = navbarColorInput.value;
        const sidebarColor = sidebarColorInput.value;
        const textColor = textColorInput.value;
        const fontSize = fontSizeInput.value;
        const darkMode = darkModeToggle.checked;

        // Save customizations to local storage
        localStorage.setItem('navbarColor', navbarColor);
        localStorage.setItem('sidebarColor', sidebarColor);
        localStorage.setItem('textColor', textColor);
        localStorage.setItem('fontSize', fontSize);
        localStorage.setItem('darkMode', darkMode);

        // Upload logo file and save URL to local storage
        const logoFile = logoUploadInput.files[0];
        if (logoFile) {
          const reader = new FileReader();
          reader.onload = function(e) {
            const logoUrl = e.target.result;
            localStorage.setItem('logoUrl', logoUrl);
            updateUI(navbarColor, sidebarColor, textColor, fontSize, darkMode, logoUrl);
          };
          reader.readAsDataURL(logoFile);
        } else {
          updateUI(navbarColor, sidebarColor, textColor, fontSize, darkMode, logoUrl);
        }

        // Close modal
        const customizeModal = document.getElementById('customizeModal');
        const modalInstance = bootstrap.Modal.getInstance(customizeModal);
        modalInstance.hide();
      });

      function updateUI(navbarColor, sidebarColor, textColor, fontSize, darkMode, logoUrl) {
        const navbar = document.getElementById('navbar');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('main');

        navbar.style.backgroundColor = navbarColor;
        sidebar.style.backgroundColor = sidebarColor;
        sidebar.querySelectorAll('.list-group-item').forEach(item => item.style.color = textColor);
        mainContent.style.color = textColor;
        mainContent.style.fontSize = `${fontSize}px`;

        if (darkMode) {
          document.body.classList.add('dark-mode');
          navbar.classList.add('navbar-dark-mode');
          sidebar.classList.add('sidebar-dark-mode');
        } else {
          document.body.classList.remove('dark-mode');
          navbar.classList.remove('navbar-dark-mode');
          sidebar.classList.remove('sidebar-dark-mode');
        }

        const navbarLogo = document.getElementById('navbarLogo');
        navbarLogo.src = logoUrl;
      }
    });
  </script>


  <!-- Populate subscription details -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      fetch('/subscriptions')
        .then(response => response.json())
        .then(subscriptions => {
          const subscriptionDetails = document.getElementById('subscriptionDetails');
          subscriptionDetails.innerHTML = ''; // Clear existing items

          subscriptions.forEach(subscription => {
            const subscriptionContainer = document.createElement('div');
            subscriptionContainer.className = 'subscription-container';

            const planTypeDiv = document.createElement('div');
            planTypeDiv.textContent = `Plan Type: ${subscription.plan_type}`;
            planTypeDiv.className = 'subscription-plan-type';

            const descriptionDiv = document.createElement('div');
            descriptionDiv.textContent = `Description: ${subscription.description}`;
            descriptionDiv.className = 'subscription-description';

            const priceDiv = document.createElement('div');
            priceDiv.textContent = `Monthly Price: ${subscription.monthly_price}`;
            priceDiv.className = 'subscription-price';

            subscriptionContainer.appendChild(planTypeDiv);
            subscriptionContainer.appendChild(descriptionDiv);
            subscriptionContainer.appendChild(priceDiv);

            subscriptionDetails.appendChild(subscriptionContainer);
          });
        })
        .catch(error => console.error('Error fetching subscriptions:', error));
    });
  </script>
</body>

</html>