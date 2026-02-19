@include('body.headerlink')

@php
    use Illuminate\Support\Facades\Auth;

    $intern = Auth::guard('intern')->user();
@endphp

<body>

    @if(!$intern)
        <div class="alert alert-danger m-4">
            Session expired. Please login again.
        </div>
    @else

        <div class="">
            <div>
                @include('body.empHeader')

                <section class="myBodySection">

                    {{-- ================= PAGE HEADER ================= --}}
                    <div class="conWrepper mb-4">
                        <div class="myConSm">
                            <div class="d-flex mb-0 align-items-center">
                                <a href="/"><ion-icon name="home-outline" style="margin-bottom: -2px;"></ion-icon></a>
                                <span class="mx-2">/</span>
                                <p class="mb-0">Profile Management</p>
                            </div>
                            <h2 class="text-dark fw-bold">Profile Management</h2>
                            <p class="mb-0">Update and manage your employee information securely</p>
                        </div>
                    </div>

                    {{-- WELCOME CARD --}}
                    <div class="conWrepper mb-4">
                        <div class="myConSm">
                            <div class="myCard primaryCard">
                                <div class="perentCardFlex">
                                    <div class="welcomeFlex">

                                        <div>
                                            <h3 class="mb-2">{{ $intern->name }}</h3>

                                        </div>
                                    </div>


                                </div>
                                <div class="myCardFoot d-flex">
                                    <p><ion-icon name="id-card-outline"></ion-icon>
                                        {{ $intern->intern_code }}</p>
                                    <p class="mx-4">|</p>

                                    <p><ion-icon name="key-outline"></ion-icon>
                                        pass4454</p>
                                    <p class="mx-4">|</p>
                                    <p><ion-icon name="cafe-outline"></ion-icon>
                                        Software Developer</p>
                                </div>




                            </div>
                        </div>
                    </div>
                    <div class="conWrepper mb-4">
                        <div class="myConSm">
                            <div class="row profileSec">
                                <div class="col-md-4">
                                    <div class="whiteBigCard mb-4">
                                        <h4 class="mb-3">
                                            <ion-icon name="camera-outline" role="img" class="md hydrated"></ion-icon>
                                            Profile Photo
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <!-- Profile Image -->
                                                <div class="profileImg">
                                                    <img id="profilePreview"
                                                        src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp"
                                                        class="rounded-circle" alt="Avatar" />
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="ms-3">
                                                    <p>
                                                        Upload a professional photo for your profile.
                                                        Accepted formats: JPG, PNG. Maximum size: 5MB.
                                                    </p>

                                                    <!-- Hidden File Input -->
                                                    <input type="file" id="profileInput" accept="image/png, image/jpeg"
                                                        style="display: none;" />

                                                    <!-- Buttons Hidden Initially -->
                                                    <form action="" method="POST" enctype="multipart/form-data">

                                                        <!-- Save Button -->
                                                        <button type="submit" id="saveBtn" class="myBtn myBtnSuccess mb-2"
                                                            style="display: none;">
                                                            <ion-icon name="download-outline"></ion-icon>
                                                            Save Changes
                                                        </button>

                                                        <!-- Cancel Button -->
                                                        <button type="button" id="cancelBtn"
                                                            class="myBtn align-items-center" style="display: none;">
                                                            <ion-icon name="close-outline"></ion-icon>
                                                            Cancel
                                                        </button>

                                                    </form>
                                                </div>
                                            </div>





                                        </div>


                                        <!-- JS Script -->
                                        <script>
                                            const profilePreview = document.getElementById("profilePreview");
                                            const profileInput = document.getElementById("profileInput");

                                            const saveBtn = document.getElementById("saveBtn");
                                            const cancelBtn = document.getElementById("cancelBtn");

                                            // Store Default Image
                                            const defaultImage = profilePreview.src;

                                            // Click Image → Open File Picker
                                            profilePreview.addEventListener("click", () => {
                                                profileInput.click();
                                            });

                                            // When User Selects Image
                                            profileInput.addEventListener("change", function () {
                                                const file = this.files[0];

                                                if (file) {
                                                    // Preview Selected Image
                                                    const reader = new FileReader();

                                                    reader.onload = function (e) {
                                                        profilePreview.src = e.target.result;
                                                    };

                                                    reader.readAsDataURL(file);

                                                    // Show Buttons
                                                    saveBtn.style.display = "inline-flex";
                                                    cancelBtn.style.display = "inline-flex";
                                                }
                                            });

                                            // Cancel Button Click → Reset Everything
                                            cancelBtn.addEventListener("click", function () {
                                                // Reset Image
                                                profilePreview.src = defaultImage;

                                                // Clear Input File
                                                profileInput.value = "";

                                                // Hide Buttons Again
                                                saveBtn.style.display = "none";
                                                cancelBtn.style.display = "none";
                                            });
                                        </script>




                                    </div>
                                    <a href="{{ route('logout') }}" class="myBtn myBtnDanger"><ion-icon
                                            name="power-outline"></ion-icon>
                                        Logout</a>
                                </div>
                                <div class="col-md-8">
                                    <div class="whiteBigCard mb-3">
                                        <h4 class="mb-3">
                                            <ion-icon name="person-outline"></ion-icon>
                                            Personal Information
                                        </h4>
                                        <form method="" action="" class="myForm personalForm">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label>Full Name</label>
                                                    <input type="text" name="name" value="{{ $intern->name }}"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label>Designation</label>
                                                    <input type="text" name="designation" value="Software Developer"
                                                        class="form-control" readonly>
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label>Date of Birth </label>
                                                    <input type="date" name="dob" value="" class="form-control">
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label>Gender</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label>Blood Group</label>
                                                    <select name="blood" class="form-control">
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
                                                <div class="col-md-6 mb-2">
                                                    <label>Marital Status</label>
                                                    <select name="marital" class="form-control">
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                        <option value="Widowed">Widowed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label>Nationality</label>
                                                    <input type="text" name="nationality" value="" class="form-control">
                                                </div>

                                                <div class="col-md-12 gap-2 personalSubmit" style="display: none">
                                                    <!-- Save Button -->
                                                    <button type="submit" id="saveBtn" class="myBtn myBtnSuccess mb-2">
                                                        <ion-icon name="download-outline"></ion-icon>
                                                        Save Changes
                                                    </button>


                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <script>
                                        const personalForm = document.querySelector(".personalForm");
                                        const personalSubmit = document.querySelector(".personalSubmit");

                                        // Detect any change in the form inputs/selects
                                        personalForm.addEventListener("input", function () {
                                            personalSubmit.style.display = "flex";
                                        });

                                        personalForm.addEventListener("change", function () {
                                            personalSubmit.style.display = "flex";
                                        });
                                    </script>



                                    <div class="whiteBigCard">
                                        <h4 class="mb-3">
                                            <ion-icon name="call-outline"></ion-icon>
                                            Contact Information
                                        </h4>
                                        <form method="" action="" class="myForm contactForm">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label>Email Address</label>
                                                    <input type="email" name="email" value="" class="form-control">
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <label>Phone Number </label>
                                                    <input type="number" name="phone" value="" class="form-control">
                                                </div>

                                                <div class="col-md-12 mb-2">
                                                    <label>Address</label>

                                                    <textarea name="address" id="" class="form-control"></textarea>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label>City</label>
                                                    <input type="text" name="city" value="" class="form-control">
                                                </div>

                                                <div class="col-md-4 mb-2">
                                                    <label>State</label>
                                                    <input type="text" name="state" value="" class="form-control">
                                                </div>
                                                <div class="col-md-4 mb-4">
                                                    <label>Pincode</label>
                                                    <input type="number" name="pin" value="" class="form-control">
                                                </div>

                                                <div class="col-md-12 gap-2 contectSubmit" style="display: none;">
                                                    <!-- Save Button -->
                                                    <button type="submit" id="saveBtn" class="myBtn myBtnSuccess mb-2">
                                                        <ion-icon name="download-outline"></ion-icon>
                                                        Save Changes
                                                    </button>


                                                </div>
                                            </div>

                                            <script>
                                                const contactForm = document.querySelector(".contactForm");
                                                const contectSubmit = document.querySelector(".contectSubmit");

                                                // Detect any change in the form inputs/selects
                                                contactForm.addEventListener("input", function () {
                                                    contectSubmit.style.display = "flex";
                                                });

                                                contactForm.addEventListener("change", function () {
                                                    contectSubmit.style.display = "flex";
                                                });
                                            </script>


                                        </form>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </section>

                @include('body.empFooter')
            </div>
        </div>

    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>