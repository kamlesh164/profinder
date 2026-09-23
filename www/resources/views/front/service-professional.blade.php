
<section class="sp-hero-section">
  <div class="sp-hero-container">
    <div class="sp-hero-content" data-aos="fade-right">
      <h1 class="sp-title">SERVICE PROFESSIONAL</h1>
      <div class="sp-breadcrumb">Home / Service Professional</div>
    </div>
    <div class="sp-hero-bg" data-aos="fade-left" data-aos-delay="300"></div>
  </div>
</section>

<section class="signup-section lr-padding py-5">
  <div class="container">
    <div class="row justify-content-center align-items-center g-4">
      <!-- Left side, vertically centered image -->
      <div class="col-lg-6 d-flex justify-content-center align-items-center" data-aos="fade-right">
        <div class="signup-image-box text-center w-100">
          <img src="{{ asset('images/bg6.png') }}" alt="Signup" class="signup-main-img mx-auto d-block">
        </div>
      </div>
      <!-- Right side, form -->
      <div class="col-lg-6" data-aos="fade-left" data-aos-delay="120">
        <div class="signup-form-box p-lg-4 rounded-4 shadow-sm">
          <h2 class="text-center mb-4 fw-bold text-white" data-aos="fade-down" data-aos-delay="180">Signup</h2>
          <div id="formErrorBox" class="alert alert-danger d-none"></div>
          <form id="signupForm" class="signup-form-box p-lg-4 rounded-4">
            <div class="mb-3" data-aos="fade-up" data-aos-delay="200">
              <label class="form-label">Full Name *</label>
              <input type="text" class="form-control" placeholder="Full Name" name="name" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="220">
              <label class="form-label">Email *</label>
              <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="240">
              <label class="form-label">Password *</label>
              <input type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="260">
              <label class="form-label">Confirm Password *</label>
              <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="280">
              <label class="form-label">Phone *</label>
              <div class="input-group">
                <span class="input-group-text" style="height: 46px">+91</span>
                <input type="tel"
                      class="form-control"
                      placeholder="XXXXXXXXXX"
                      name="phone"
                      oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                      maxlength="10"
                      required>
              </div>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="320">
                <label class="form-label">State *</label>
                <select id="stateSelect" class="form-control" name="state" required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="340">
              <label class="form-label">City *</label>
                <select id="citySelect" class="form-control" name="city" required>
                    <option value="">Select City</option>
                </select>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="300">
              <label class="form-label">Address *</label>
              <input type="text" class="form-control" placeholder="Address" name="address" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="300">
              <label class="form-label">Pincode *</label>
              <input type="text" class="form-control" placeholder="Pincode" name="pincode" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="360">
              <label class="form-label">Website *</label>
              <input type="text" class="form-control" placeholder="Website" name="website" required>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="380">
              <label class="form-label">Gender</label>
              <select class="form-select" name="gender">
                <option value="Male" selected>Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="mb-3" data-aos="fade-up" data-aos-delay="400">
              <label class="form-label">Vendor Type <span class="">*</span></label>
              <select class="form-select" name="type" required>
                <option value="">Select type</option>
                @foreach (config('static_data.accountType') as $role => $value )
                  @if($role !== 'customer') {{-- ya $value !== 1 --}}
                      <option value="{{ $value }}">{{ ucfirst($role) }}</option>
                  @endif
                @endforeach
              </select>
            </div>

            <div class="mb-3" data-aos="fade-up" data-aos-delay="420">
              <label class="form-label">Category <span class="">*</span></label>
              <select class="form-select" name="category_id" required>
                <option value="">Select category</option>
                @foreach ($categories as $data )
                  <option value="{{ $data['id'] }}">{{ $data['name'] }}</option>
                @endforeach
              </select>
            </div>
            <button type="submit" class="btn btn-orange w-100 mt-3" data-aos="zoom-in" data-aos-delay="440">Sign Up</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow-lg">
      <div class="modal-body text-center py-5">
        <!-- Success SVG Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 64 64">
          <circle cx="32" cy="32" r="32" fill="#e9f6ee"/>
          <path d="M19 33.5l9 9 17-17" stroke="#27ae60" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h4 class="mt-4 fw-bold text-success" id="successModalLabel">Signup Successful!</h4>
        <p class="text-muted mb-4">Your account has been created.</p>
        <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
