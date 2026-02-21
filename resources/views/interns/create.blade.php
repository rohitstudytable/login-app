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
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" name="designation">
                        </div>



                        <div class="form-group">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select name="gender" required>
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="dob" required>
                        </div>

                        <div class="form-group">
                            <label>Blood Group <span class="text-danger">*</span></label>
                            <select name="blood_group" required>
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Marital Status <span class="text-danger">*</span></label>
                            <select name="marital_status" required>
                                <option value="">Select</option>
                                <option value="single">Single
                                </option>
                                <option value="married">
                                    Married
                                </option>
                                <option value="divorced">
                                    Divorced</option>
                                <option value="widowed">
                                    Widowed
                                </option>
                            </select>
                        </div>




                        <div class="form-group">
                            <label>Role <span class="text-danger">*</span></label>
                            <select name="role" required>
                                <option value="">Select Role</option>
                                <option value="intern">Intern</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Intern / Employee Code <span class="text-danger">*</span></label>
                            <input type="text" name="intern_code" required>
                            <small class="helper-text">🔐 Password auto-generated</small>
                        </div>

                        <div class="form-group">
                            <label>Profile Image <span class="text-danger">*</span></label>
                            <input type="file" name="img" accept="image/*" required>
                            <div class="helper-text">
                                Max file size: <strong>5 MB</strong> (JPG, JPEG, PNG)
                            </div>
                        </div>


                    </div>
                    <h5 class="mt-4">Contact Information</h5>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <input type="number" name="contact" value="{{ old('contact') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required>
                        </div>



                        <!-- ADDRESS -->
                        <div class="form-group">
                            <label>Address <span class="text-danger">*</span></label>
                            <textarea name="address" rows="3" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>City <span class="text-danger">*</span></label>
                            <input type="text" name="city" value="{{ old('city') }}" required>
                        </div>

                        <div class="form-group">
                            <label>State <span class="text-danger">*</span></label>
                            <input type="text" name="state" value="{{ old('state') }}" required>
                        </div>

                        <div class="form-group">
                            <label>PIN Code <span class="text-danger">*</span></label>
                            <input type="number" name="pin" value="{{ old('pin') }}" required>
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