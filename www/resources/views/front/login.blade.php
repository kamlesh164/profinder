<section class="login-bg py-5 px-3 px-md-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6 col-lg-6">
        <div class="form-bg rounded-3 shadow p-4">
          <h2 class="text-center mb-4 fw-bold text-white">Sign In</h2>
          <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="login-phone" class="form-label text-white">Phone *</label>
              <input type="tel"
                     id="login-phone"
                     name="phone"
                     class="form-control"
                     placeholder="Phone"
                     required
                     pattern="\d{10}"
                     maxlength="10"
                     value="{{ old('phone') }}">
              @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="login-password" class="form-label text-white">Password *</label>
              <input type="password"
                     id="login-password"
                     name="password"
                     class="form-control"
                     placeholder="Password"
                     required>
              @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>
            <button type="submit" class="btn btn-orange w-100 mt-3 fw-bold">
              Sign In
            </button>
            {{-- Optional: Forgot password link --}}
            <div class="text-end mt-2">
              <a href="{{-- {{ route('password.request') }} --}}" class="txt-muted small">Forgot password?</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
