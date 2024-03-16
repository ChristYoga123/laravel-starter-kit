@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            @if (auth('admin')->user()->can('admins.admin.create'))
                <div class="mb-4" style="width: 15%">
                    <a href="{{ route('admin.settings.administrators.create') }}" class="btn btn-primary mb-2 text-nowrap">
                        Add {{ $title }}
                    </a>
                </div>
            @endif

            <!-- Permission Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    {{ $dataTable->table(['class' => 'datatables table border-top']) }}
                </div>
            </div>
            <!--/ SurveyCategory Table -->
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
@endsection
@push('scripts')
    {{ $dataTable->scripts() }}
    @if (session('success'))
        <script>
            Swal.fire(
                'Success!',
                '{{ session('success') }}',
                'success'
            )
        </script>
    @elseif($errors->any())
        <script>
            Swal.fire(
                'Error!',
                `Terdapat kesalahan saat menambahkan data baru. Mohon periksa kembali form yang diisi`,
                'error'
            )
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire(
                'Error!',
                '{{ session('error') }}',
                'error'
            )
        </script>
    @endif
    <script>
        function deleteAdmin(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this data!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`form#deleteAdmin${id}`).submit();
                }
            })
        }
    </script>
@endpush
