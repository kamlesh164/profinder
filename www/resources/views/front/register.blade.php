<section class="login-bg py-5 px-3 px-md-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="form-bg rounded-3 shadow p-4">
          <h2 class="text-center mb-4 fw-bold text-white">Sign Up</h2>
          <form action="{{ route('register') }}" method="POST" class="p-4">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label text-white">Full Name *</label>
                <input type="text" name="name" id="name"
                      class="form-control @error('name') is-invalid @enderror"
                      value="{{ old('name') }}" required placeholder="Full Name">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label text-white">Email *</label>
                <input type="email" name="email" id="email"
                      class="form-control @error('email') is-invalid @enderror"
                      value="{{ old('email') }}" required placeholder="Email">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label text-white">Password *</label>
                <input type="password" name="password" id="password"
                      class="form-control @error('password') is-invalid @enderror"
                      required placeholder="Password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label text-white">Confirm Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                      class="form-control"
                      required placeholder="Confirm Password">
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label text-white">Phone *</label>
                <input type="tel" name="phone" id="phone"
                      class="form-control @error('phone') is-invalid @enderror"
                      value="{{ old('phone') }}"
                      required pattern="\d{10}" maxlength="10" placeholder="Phone">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label text-white">Address *</label>
                <input type="text" name="address" id="address"
                      class="form-control @error('address') is-invalid @enderror"
                      value="{{ old('address') }}" required placeholder="Address">
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="city" class="form-label text-white">City *</label>
                <input type="text" name="city" id="city"
                      class="form-control @error('city') is-invalid @enderror"
                      value="{{ old('city') }}" required placeholder="City">
                @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="state" class="form-label text-white">State *</label>
                <input type="text" name="state" id="state"
                      class="form-control @error('state') is-invalid @enderror"
                      value="{{ old('state') }}" required placeholder="State">
                @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-3">
                <label for="user_type" class="form-label text-white">User Type</label>
                <select name="user_type" id="user_type" class="form-select">
                    <option value="1" {{ old('user_type') == '1' ? 'selected' : '' }}>Builder</option>
                    <option value="2" {{ old('user_type') == '2' ? 'selected' : '' }}>Contractor</option>
                    <option value="3" {{ old('user_type') == '3' ? 'selected' : '' }}>Individual</option>
                </select>
            </div>

            <button type="submit" class="btn btn-orange w-100 mt-2 fw-bold">
                Sign Up
            </button>
        </form>

        </div>
      </div>
    </div>
  </div>
</section>
