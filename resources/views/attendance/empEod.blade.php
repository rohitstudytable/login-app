@include('body.headerlink')

<body>

    <div class="">
        <div>
            @include('body.empHeader')

            <section class="myBodySection">

                {{-- PAGE HEADER --}}
                <div class="conWrepper mb-4">
                    <div class="myConSm">
                        <div class="d-flex mb-0 align-items-center">
                            <a href="/">
                                <ion-icon name="home-outline" style="margin-bottom: -2px;"></ion-icon>
                            </a>
                            <span class="mx-2">/</span>
                            <p class="mb-0">EOD Reports</p>
                        </div>

                        <h2 class="text-dark fw-bold">End Of Day (EOD) Report</h2>
                        <p class="mb-0">
                            Submit your daily work update and track previous submissions.
                        </p>
                    </div>
                </div>

                <div class="conWrepper">
                    <div class="myConSm">

                        {{-- SUCCESS MESSAGE --}}
                        @if (session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- VALIDATION ERRORS --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- EOD FORM --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="whiteBigCard">

                                    <h4 class="mb-2">
                                        <ion-icon name="document-text-outline"></ion-icon>
                                        Submit Today's EOD
                                    </h4>

                                    <p class="text-primary fw-semibold mb-3">
                                        {{-- <ion-icon name="calendar-outline"></ion-icon> --}}
                                        <span id="eodDateTime"></span>
                                    </p>

                                    <form action="{{ route('empeod.store') }}" method="POST" class="myForm">
                                        @csrf

                                        <div class="mb-3">
                                            <label>EOD Report *</label>
                                            <textarea name="tasks_completed" rows="8" class="form-control" placeholder="Describe today's work..." required>{{ old('tasks_completed') }}</textarea>
                                        </div>

                                        <button type="submit" class="myBtn myBtnPrimary">
                                            <ion-icon name="save-outline"></ion-icon>
                                            Submit EOD
                                        </button>

                                    </form>

                                </div>
                            </div>
                        </div>

                        {{-- RECENT EODS --}}
                        <div class="row">
                            <div class="col-md-12">

                                <div class="whiteBigCard">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">
                                            <ion-icon name="list-outline"></ion-icon>
                                            Recent EOD Reports
                                        </h4>
                                    </div>

                                    <div class="mytableCon">

                                        <table class="table table-bordered paginationTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>EOD Report</th>
                                                    <th>Submitted At</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @forelse($reports as $report)
                                                    <tr>

                                                        <td width="140">
                                                            {{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}
                                                        </td>

                                                        <td>
                                                            {!! nl2br(e($report->tasks_completed)) !!}
                                                        </td>

                                                        <td width="180">
                                                            {{ $report->created_at->format('d M Y h:i A') }}
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4">
                                                            No EOD reports submitted yet.
                                                        </td>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </section>

            @include('body.empFooter')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.paginationTable').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: false,
                searching: false,
                info: true
            });
        });
    </script>

    <script>
        function updateEodDateTime() {
            const now = new Date();

            const date = now.toLocaleDateString('en-IN', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            const time = now.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            document.getElementById('eodDateTime').innerHTML =
                `${date} | ${time}`;
        }

        updateEodDateTime();
        setInterval(updateEodDateTime, 1000);
    </script>

</body>
