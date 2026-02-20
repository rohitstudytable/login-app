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
                                            {{-- <small class="text-muted">{{ $intern->email }}</small> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="myCardFoot d-flex align-items-center flex-wrap">
                                    <p>
                                        <ion-icon name="id-card-outline"></ion-icon>
                                        {{ $intern->intern_code }}
                                    </p>

                                    <p class="mx-3">|</p>

                                    <p>
                                        <ion-icon name="key-outline"></ion-icon>
                                        {{ ucfirst($intern->plain_password) }}
                                    </p>

                                    <p class="mx-3">|</p>

                                    <p>
                                        <ion-icon name="person-outline"></ion-icon>
                                        {{ ucfirst($intern->role) }}
                                    </p>
                                    
                                    <p class="mx-3">|</p>

                                    <p>
                                        <ion-icon name="cafe-outline"></ion-icon>
                                        {{ $intern->designation ?? 'Not Assigned' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- END  --}}

                {{-- IMG CARD --}}
                    <div class="conWrepper mb-4">
                        <div class="myConSm">
                            <div class="row profileSec">
                                <div class="col-md-4">
                                  <div class="whiteBigCard mb-4">
                                    <h4 class="mb-3">
                                        <ion-icon name="camera-outline"></ion-icon>
                                        Profile Photo
                                    </h4>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="profileImg">
                                                <img id="profilePreview"
                                                    src="{{ $intern->img
                                                        ? asset('storage/'.$intern->img)
                                                        : asset('images/default-user.png') }}"
                                                    class="rounded-circle"
                                                    alt="Profile Image"
                                                    style="cursor:pointer">
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="ms-3">
                                                <p>
                                                    Upload a professional photo.
                                                    Accepted formats: JPG, PNG. Maximum size: 5MB.
                                                </p>

                                                <form action="{{ route('intern.profile.image') }}"
                                                    method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf

                                                    <!-- Hidden File Input -->
                                                    <input type="file"
                                                        id="profileInput"
                                                        name="img"
                                                        accept="image/png, image/jpeg"
                                                        hidden>

                                                    <!-- Save Button -->
                                                    <button type="submit"
                                                            id="saveBtn"
                                                            class="myBtn myBtnSuccess mb-2"
                                                            style="display:none">
                                                        <ion-icon name="download-outline"></ion-icon>
                                                        Save Changes
                                                    </button>

                                                    <!-- Cancel Button -->
                                                    <button type="button"
                                                            id="cancelBtn"
                                                            class="myBtn align-items-center"
                                                            style="display:none">
                                                        <ion-icon name="close-outline"></ion-icon>
                                                        Cancel
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        const profilePreview = document.getElementById("profilePreview");
                                        const profileInput = document.getElementById("profileInput");
                                        const saveBtn = document.getElementById("saveBtn");
                                        const cancelBtn = document.getElementById("cancelBtn");

                                        const defaultImage = profilePreview.src;

                                        profilePreview.addEventListener("click", () => {
                                            profileInput.click();
                                        });

                                        profileInput.addEventListener("change", function () {
                                            const file = this.files[0];

                                            if (file) {
                                                profilePreview.src = URL.createObjectURL(file);
                                                saveBtn.style.display = "inline-flex";
                                                cancelBtn.style.display = "inline-flex";
                                            }
                                        });

                                        cancelBtn.addEventListener("click", function () {
                                            profilePreview.src = defaultImage;
                                            profileInput.value = "";
                                            saveBtn.style.display = "none";
                                            cancelBtn.style.display = "none";
                                        });
                                    </script>
                                </div>

                                    <form method="POST" action="{{ route('intern.logout') }}">
                                        @csrf
                                        <button type="submit" class="myBtn myBtnDanger">
                                            <ion-icon name="power-outline"></ion-icon>
                                            Logout
                                        </button>
                                    </form>

                                </div>

                {{------------------END-----------------------------------}}

                                <div class="col-md-8">
            {{-- ================= PERSONAL INFORMATION CARD ================= --}}
                <div class="whiteBigCard mb-3">
                    <h4 class="mb-3">
                        <ion-icon name="person-outline"></ion-icon>
                        Personal Information
                    </h4>

                    <form method="POST"
                        action="{{ route('intern.profile.personal') }}"
                        class="myForm personalForm">
                        @csrf

                        <div class="row">
                            {{-- NAME --}}
                            <div class="col-md-6 mb-2">
                                <label>Full Name</label>
                                <input type="text"
                                    name="name"
                                    value="{{ old('name', $intern->name) }}"
                                    class="form-control">
                            </div>

                            {{-- DESIGNATION --}}
                            <div class="col-md-6 mb-4">
                                <label>Designation</label>
                                <input type="text"
                                    name="designation"
                                    value="{{ old('designation', $intern->designation) }}"
                                    class="form-control"
                                    readonly>
                            </div>

                            {{-- DOB --}}
                            <div class="col-md-4 mb-2">
                                <label>Date of Birth</label>
                                <input type="date"
                                    name="dob"
                                    value="{{ old('dob', optional($intern->dob)->format('Y-m-d')) }}"
                                    class="form-control">
                            </div>


                            {{-- GENDER --}}
                            <div class="col-md-4 mb-2">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="male" {{ $intern->gender === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $intern->gender === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $intern->gender === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            {{-- BLOOD GROUP --}}
                            <div class="col-md-4 mb-2">
                                <label>Blood Group</label>
                                <select name="blood_group" class="form-control">
                                    <option value="">Select</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                                        <option value="{{ $bg }}"
                                            {{ $intern->blood_group === $bg ? 'selected' : '' }}>
                                            {{ $bg }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- MARITAL STATUS --}}
                            <div class="col-md-6 mb-2">
                                <label>Marital Status</label>
                                <select name="marital_status" class="form-control">
                                    <option value="">Select</option>
                                    <option value="single" {{ $intern->marital_status === 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ $intern->marital_status === 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ $intern->marital_status === 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ $intern->marital_status === 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>

                            {{-- NATIONALITY --}}
                            <div class="col-md-6 mb-4">
                                <label>Nationality</label>
                                <input type="text"
                                    name="nationality"
                                    value="{{ old('nationality', $intern->nationality) }}"
                                    class="form-control">
                            </div>

                            {{-- SAVE BUTTON --}}
                            <div class="col-md-12 gap-2 personalSubmit" style="display:none">
                                <button type="submit" class="myBtn myBtnSuccess mb-2">
                                    <ion-icon name="download-outline"></ion-icon>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ================= SHOW SAVE BUTTON ON CHANGE ================= --}}
                <script>
                    const personalForm = document.querySelector('.personalForm');
                    const personalSubmit = document.querySelector('.personalSubmit');

                    personalForm.addEventListener('input', () => {
                        personalSubmit.style.display = 'flex';
                    });

                    personalForm.addEventListener('change', () => {
                        personalSubmit.style.display = 'flex';
                    });
                </script>
            {{-- ================= END ================= --}}

            
        {{-- ------------------------- CONTACT INFO ----------------------------------- --}}
            <div class="whiteBigCard">
                <h4 class="mb-3">
                    <ion-icon name="call-outline"></ion-icon>
                    Contact Information
                </h4>

                <form method="POST"
                    action="{{ route('intern.profile.contact') }}"
                    class="myForm contactForm">

                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Email Address</label>
                            <input type="email"
                                name="email"
                                value="{{ $intern->email }}"
                                class="form-control"
                                >
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Phone Number</label>
                            <input type="number"
                                name="contact"
                                value="{{ $intern->contact }}"
                                class="form-control">
                        </div>

                        <div class="col-md-12 mb-2">
                            <label>Address</label>
                            <textarea name="address"
                                    class="form-control">{{ $intern->address }}</textarea>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>City</label>
                            <input type="text"
                                name="city"
                                value="{{ $intern->city }}"
                                class="form-control">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label>State</label>
                            <input type="text"
                                name="state"
                                value="{{ $intern->state }}"
                                class="form-control">
                        </div>

                        <div class="col-md-4 mb-4">
                            <label>Pincode</label>
                            <input type="number"
                                name="pin"
                                value="{{ $intern->pin }}"
                                class="form-control">
                        </div>

                        <div class="col-md-12 gap-2 contectSubmit" style="display:none;">
                            <button type="submit" class="myBtn myBtnSuccess mb-2">
                                <ion-icon name="download-outline"></ion-icon>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                    const contactForm = document.querySelector(".contactForm");
                    const contectSubmit = document.querySelector(".contectSubmit");

                    contactForm.addEventListener("input", () => {
                        contectSubmit.style.display = "flex";
                    });

                    contactForm.addEventListener("change", () => {
                        contectSubmit.style.display = "flex";
                    });
                </script>
        {{-- ------------------ END -------------------------------- --}}


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