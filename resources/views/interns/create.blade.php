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
                <h2>Add Intern / Employee</h2>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="form-card">



                <form method="POST" action="{{ route('interns.store') }}" enctype="multipart/form-data">
                    @csrf
                    <h5>Personal Information</h5>
                    <div class="form-grid">

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" name="designation">
                        </div>



                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob">
                        </div>

                        <div class="form-group">
                            <label>Blood Group</label>
                            <select name="blood_group">
                                <option value="">Select</option>
                                <option>A+</option>
                                <option>B+</option>
                                <option>O+</option>
                                <option>AB+</option>
                            </select>
                        </div>




                        <div class="form-group">
                            <label>Intern / Employee Code</label>
                            <input type="text" name="intern_code" required>
                            <small class="helper-text">🔐 Password auto-generated</small>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="intern">Intern</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="img" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Nationality</label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}">
                        </div>

                    </div>
                    <h5 class="mt-4">Contact Information</h5>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="number" name="contact" value="{{ old('contact') }}">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                        </div>



                        <!-- ADDRESS -->
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" value="{{ old('city') }}">
                        </div>

                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" value="{{ old('state') }}">
                        </div>

                        <div class="form-group">
                            <label>PIN Code</label>
                            <input type="number" name="pin" value="{{ old('pin') }}">
                        </div>


                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn-primary">
                            <i class="fa fa-save"></i> Save Details
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</body>

</html>