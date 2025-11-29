<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Jogja Artsphere</title>
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
  <div class="container">
    <!-- LEFT SECTION -->
    <div class="left-section">
      <div class="left-content">
        <h1>Create Your<br>Account</h1>
        <p>
          Join Jogja Artsphere to explore a marketplace full of original artworks and handmade creations. Connect with talented artists, support their craft, and be part of a growing creative network in Yogyakarta.
        </p>
      </div>
    </div>

    <!-- RIGHT SECTION -->
    <div class="right-section">
      <h2>Sign Up</h2>

      <img class="ornament-bottom" src="{{ asset('assets/w.png') }}" alt="bottom decoration">

      <!-- FORM REGISTRASI -->
      <form action="{{ url('/register') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="nama" class="{{ $errors->has('nama') ? 'error-label' : '' }}">Username</label>
          <input type="text" id="nama" name="nama" placeholder="Enter your username" value="{{ old('nama') }}" class="{{ $errors->has('nama') ? 'error-input' : '' }}" required>
          @error('nama')
            <small class="error-text">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-group">
          <label for="email" class="{{ $errors->has('email') ? 'error-label' : '' }}">Email Address</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'error-input' : '' }}" required>
          @error('email')
            <small class="error-text">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-group">
          <label for="no_hp" class="{{ $errors->has('no_hp') ? 'error-label' : '' }}">Phone Number</label>
          <input type="tel" id="no_hp" name="no_hp" placeholder="Enter your phone number" value="{{ old('no_hp') }}" class="{{ $errors->has('no_hp') ? 'error-input' : '' }}" required>
          @error('no_hp')
            <small class="error-text">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-group">
          <label for="password" class="{{ $errors->has('password') ? 'error-label' : '' }}">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter password" class="{{ $errors->has('password') ? 'error-input' : '' }}" required>
          @error('password')
            <small class="error-text">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-group">
          <label for="password_confirmation" class="{{ $errors->has('password_confirmation') ? 'error-label' : '' }}">Confirm Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" class="{{ $errors->has('password_confirmation') ? 'error-input' : '' }}" required>
          @error('password_confirmation')
            <small class="error-text">{{ $message }}</small>
          @enderror
        </div>

        <div class="form-group">
          <label for="role" class="{{ $errors->has('role') ? 'error-label' : '' }}">Role</label>
          <select id="role" name="role" class="{{ $errors->has('role') ? 'error-input' : '' }}" required>
            <option value="">Select role</option>
            <option value="seniman" {{ old('role') == 'seniman' ? 'selected' : '' }}>Seniman</option>
            <option value="pembeli" {{ old('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
          </select>
          @error('role')
            <small class="error-text">{{ $message }}</small>
          @enderror
        </div>

        <button type="submit" class="join">Join us</button>
      </form>

      <!-- Decorative top ornament -->
      <img class="ornament-top" src="{{ asset('assets/bunga.png') }}" alt="top decoration">
    </div>

    <!-- MIDDLE DIVIDER -->
    <img class="divider-middle" src="{{ asset('assets/tengah.png') }}" alt="ornamental divider">
  </div>
</body>
</html>