<!DOCTYPE html>
<html lang="en">

<head>
    @include('body.adminHeadLink')
</head>

<body>

    @include('layouts.sidebar')

    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('interns.index') }}" class="back-btn">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
                <h2>Edit Intern / Employee</h2>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">Logout</button>
            </form>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="form-card">

                <form method="POST" action="{{ route('interns.update', $intern) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- BASIC DETAILS -->
                    <h5>Personal Information</h5>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name', $intern->name) }}" required>
                        </div>


                        <!-- WORK DETAILS -->
                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" name="designation"
                                value="{{ old('designation', $intern->designation) }}">
                        </div>
                        <!-- PERSONAL DETAILS -->
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select</option>
                                <option value="male" {{ $intern->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $intern->gender === 'female' ? 'selected' : '' }}>Female
                                </option>
                                <option value="other" {{ $intern->gender === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob"
                                value="{{ old('dob', optional($intern->dob)->format('Y-m-d')) }}">
                        </div>

                        <div class="form-group">
                            <label>Blood Group</label>
                            <select name="blood_group">
                                <option value="">Select Blood Group</option>
                                <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nationality</label>
                            <input type="text" name="nationality"
                                value="{{ old('nationality', $intern->nationality) }}">
                        </div>
                        <div class="form-group">
                            <label>Marital Status</label>
                            <select name="marital_status">
                                <option value="">Select</option>
                                <option value="single" {{ $intern->marital_status === 'single' ? 'selected' : '' }}>Single
                                </option>
                                <option value="married" {{ $intern->marital_status === 'married' ? 'selected' : '' }}>
                                    Married
                                </option>
                                <option value="divorced" {{ $intern->marital_status === 'divorced' ? 'selected' : '' }}>
                                    Divorced</option>
                                <option value="widowed" {{ $intern->marital_status === 'widowed' ? 'selected' : '' }}>
                                    Widowed
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="intern" {{ $intern->role === 'intern' ? 'selected' : '' }}>Intern</option>
                                <option value="employee" {{ $intern->role === 'employee' ? 'selected' : '' }}>Employee
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Intern / Employee Code</label>
                            <input type="text" name="intern_code" value="{{ old('intern_code', $intern->intern_code) }}"
                                required>
                        </div>

                        <!-- PASSWORD (READ ONLY) -->
                        <input type="hidden" value="{{ $intern->plain_password }}" readonly required>

                        <!-- PROFILE IMAGE -->
                        <div class="form-group">
                            <label>Profile Image</label>

                            @if($intern->img)
                                <img src="{{ asset('storage/' . $intern->img) }}" class="profile-img">
                            @endif

                            <input type="file" name="img" accept="image/*">

                            <div class="helper-text">
                                Uploading a new image will replace the existing one.<br>
                                Max file size: <strong>5 MB</strong> (JPG, JPEG, PNG)
                            </div>
                        </div>

                    </div>
                    <h5 class="mt-4">Contact Information</h5>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="contact" value="{{ old('contact', $intern->contact) }}"
                                maxlength="10" pattern="[0-9]{1,13}" inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="helper-text">
                                Only numbers allowed. Maximum 10 digits.
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $intern->email) }}" required>
                        </div>

                        <!-- ADDRESS -->
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3">{{ old('address', $intern->address) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" value="{{ old('city', $intern->city) }}">
                        </div>

                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" value="{{ old('state', $intern->state) }}">
                        </div>

                        <div class="form-group">
                            <label>PIN Code</label>
                            <input type="text" name="pin" value="{{ old('pin', $intern->pin) }}">
                        </div>
                    </div>


                    <div class="form-footer">
                        <button class="btn-primary">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>