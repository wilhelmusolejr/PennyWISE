@php
    $totalMoneyIn = $totals['money-in'] ?? 0;
    $totalMoneyOut = $totals['money-out'] ?? 0;
    $currentBalance = $totalMoneyIn - $totalMoneyOut;

    $user = Auth::user();
@endphp


<x-layout :user="$user">

    <main>
        <div class="container">
            <div class="balance-parent d-flex flex-md-row gap-2 justify-content-md-between align-items-md-center flex-column py-5">
                <div class="d-md-flex align-items-end text-center gap-2">

                    <p class="main-balance fs-1">P {{ number_format($currentBalance, 0, '.', ',') }}.00</p>
                    @if ($data->first())
                    <p class="fs-6">as of {{ date('F d, Y', strtotime($data->first()->date)) }}</p>
                    @endif

                </div>
                <div class="text-uppercase">
                    <div class="d-flex justify-content-between gap-4 fs-6">
                        <p>money-in</p>
                        <p class="balance balance-green">P {{ number_format($totalMoneyIn, 0, '.', ',') }}.00</p>
                    </div>
                    <div class="d-flex justify-content-between gap-4 fs-6">
                        <p>money-out</p>
                        <p class="balance balance-red">P {{ number_format($totalMoneyOut, 0, '.', ',') }}.00</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container container p-0 rounded-3 ">
            <div class="justify-content-md-between flex-md-row align-items-center justify-content-center flex-column d-flex gap-2 p-3">
                <div class="">
                    <div class="btn btn-primary d-none">Filter</div>
                    <form id="deleteTransactionsForm" method="POST" action="{{ route('home_dashboard') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add Transaction</button>
                    <a href="{{ url()->full() }}" class="btn btn-secondary">Refresh</a>
                </div>
            </div>

            <table class="container-fluid fs-6">
                <thead>
                    <tr class="text-uppercase">
                        <th class="text-center col-box" style="width:5%"><i class="fa-regular fa-square"></i></th>
                        <th class="text-center" style="width:5%">Type</th>
                        <th class="text-center" style="width:20%">Date</th>
                        <th style="width:40%">Title</th>
                        <th class="text-end" style="width:20%">Amount</th>
                        <th style="width:5%"> </th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data) == 0)
                        <tr class="no-data text-center">
                            <td colspan="6">You don't have any records yet.</td>
                        </tr>
                    @else
                        @foreach ($data as $row)
                            <tr class="{{ $row -> type === 'money-in' ? 'table-money-in' : 'table-money-out' }} transaction-header text-capitalize" data-transaction-id="{{ $row -> id }}">
                                <td class="text-center col-box"><i class="fa-regular fa-square"></i></td>
                                <td class="text-center"><i class="fa-solid fa-square"></i></td>
                                <td class="text-center">{{ $row -> date }}</td>
                                <td>{{ $row -> name }}</td>
                                <td class="text-end">P {{ number_format($row -> amount, 0, '.', ',') }}.00</td>
                                <td class="text-center col-eye"><i class="fa-regular fa-eye"></i></td>
                            </tr>
                            <tr class="{{ $row -> type === 'money-in' ? 'table-money-in' : 'table-money-out' }} transaction-info d-none" data-transaction-id="{{ $row -> id }}">
                                <td></td>
                                <td colspan="4">
                                    <div class="container-fluid flex-md-row flex-column d-flex gap-3 py-2 px-0">
                                        {{-- description --}}
                                        <div class="flex-grow-1">
                                            <p class="fw-semibold">Description</p>
                                            <p > {{ $row -> description }} </p>
                                        </div>
                                        @if ($row -> typetype == 'set')
                                        {{-- breakdown --}}
                                        <div class="flex-grow-1">
                                            <p class="fw-semibold">Breakdown</p>
                                            @foreach ($row -> breakdowns as $item)
                                                <div class="d-flex justify-content-between border-bottom border-dark-subtle">
                                                    <div ><p>{{ $loop->iteration.". ".$item -> name }}</p></div>
                                                    <div ><p>P {{ number_format($item->amount, 0, '.', ',') }}.00</p></div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center" >
                                    <form action="{{ url('/dashboard', ['id' => $row->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if (count($data) <= 4)
                            <tr class="empty">
                                <td colspan="6"></td>
                            </tr>
                        @endif
                    @endif
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Transaction</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="transactionForm" action="{{ route('home_dashboard') }}" method="post">
                    @csrf

                    <div class="d-flex flex-column gap-2">

                        <div class="w-100">
                            <label for="exampleInputEmail1" class="form-label">Transaction Type</label>
                            <div class="type-parent d-flex gap-3">
                                <div class="">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="transaction_type" id="money_in" value="money-in" checked>
                                        <label class="form-check-label" for="money_in">
                                        Money In
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="transaction_type" id="money_out" value="money-out">
                                        <label class="form-check-label" for="money_out">
                                        Money out
                                        </label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="transaction_typetype" id="individual" value="individual" checked>
                                        <label class="form-check-label" for="individual">
                                        Individual
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="transaction_typetype" id="set" value="set">
                                        <label class="form-check-label" for="set">
                                        Set
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2">
                            <x-input-parent class="col-md-6">
                                <x-label for="transaction_name">Transaction Name</x-label>
                                <x-input type="text" id="transaction_name" name="transaction_name" aria-describedby="transaction_name" :required="true" />
                                <x-input-error id="transaction_name"></x-input-error>
                            </x-input-parent>

                            <x-input-parent class="col-md-6">
                                <x-label for="transaction_date">Transaction Date</x-label>
                                <x-input type="date" id="transaction_date" name="transaction_date" aria-describedby="transaction_date" :required="true" />
                                <x-input-error id="transaction_date"></x-input-error>
                            </x-input-parent>
                        </div>

                        <x-input-parent class="col-md-12">
                            <x-label for="transaction_desc">Transaction Description</x-label>
                            <x-input type="text" id="transaction_desc" name="transaction_desc" aria-describedby="transaction_desc" :required="true" />
                            <x-input-error id="transaction_desc"></x-input-error>
                        </x-input-parent>

                        <x-input-parent class="col-md-12 transaction_amount_element">
                            <x-label for="transaction_amount">Transaction Amount</x-label>
                            <x-input type="number" id="transaction_amount" name="transaction_amount" aria-describedby="transaction_amount" :required="true" />
                            <x-input-error id="transaction_amount"></x-input-error>
                        </x-input-parent>

                        <div class="w-100 breakdown-parent d-none">
                            <div class="align-items-center justify-content-between d-flex py-2">
                                <p>Breakdown</p>
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="breakdown-list d-flex flex-column gap-3">
                                <div class="breakdown-item row g-2 border border-opacity-25 p-2 rounded bg-light-subtle">
                                    <x-input-parent class="col-md-5">
                                        <x-label for="bd_name">Name</x-label>
                                        <x-input type="text" id="bd_name" name="bd_name[]" aria-describedby="bd_name" :required="true" />
                                        <x-input-error id="bd_name"></x-input-error>
                                    </x-input-parent>

                                    <x-input-parent class="col-md-5">
                                        <x-label for="bd_amount">Amount</x-label>
                                        <x-input type="number" id="bd_amount" name="bd_amount[]" aria-describedby="bd_amount" :required="true" />
                                        <x-input-error id="bd_amount"></x-input-error>
                                    </x-input-parent>

                                    <div class="col-md-2 d-flex align-items-center justify-content-center p-2">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
            <a type="button" class="text-decoration-none text-dark" data-bs-dismiss="modal">Close</a>
            <button type="submit" class="btn btn-primary" form="transactionForm">Add Transaction</button>
            </div>
        </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="modal fade" id="settingModal" tabindex="-1" aria-labelledby="settingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingModalLabel">Profile Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="{{ route('user_update') }}" id="user_update" enctype="multipart/form-data" class="">
                        @csrf
                        @method('PATCH')

                        <x-loader></x-loader>

                        <div class="d-flex flex-column gap-2 form-body">

                            <x-input-parent class="col-md-12">
                                <x-label for="profile_picture">Profile Picture</x-label>
                                <x-input type="file" id="profile_picture" name="profile_picture" aria-describedby="profile_picture" :required="false" />
                                <x-input-error id="profile_picture"></x-input-error>
                            </x-input-parent>

                            <div class="row g-2">
                                <x-input-parent class="col-md-6">
                                    <x-label for="first_name">First Name</x-label>
                                    <x-input type="text" id="first_name" name="first_name" aria-describedby="first_name" :required="true" value="{{ $user->first_name }}"/>
                                    <x-input-error id="first_name"></x-input-error>
                                </x-input-parent>

                                <x-input-parent class="col-md-6">
                                    <x-label for="last_name">Last Name</x-label>
                                    <x-input type="text" id="last_name" name="last_name" aria-describedby="last_name" :required="true" value="{{ $user->last_name }}" />
                                    <x-input-error id="last_name"></x-input-error>
                                </x-input-parent>
                            </div>

                            <div class="row g-2">
                                <x-input-parent class="col-md-6">
                                    <x-label for="birthdate">Birthdate</x-label>
                                    <x-input type="date" id="birthdate" name="birthdate" aria-describedby="birthdate" :required="true" value="{{ $user->birthdate }}" />
                                    <x-input-error id="birthdate"></x-input-error>
                                </x-input-parent>

                                <x-input-parent class="col-md-6">
                                    <x-label for="email">Email address</x-label>
                                    <x-input type="email" id="email" name="email" aria-describedby="email" :required="true" value="{{ $user->email }}" readonly/>
                                    <x-input-error id="email"></x-input-error>
                                </x-input-parent>
                            </div>

                            <x-input-parent class="col-md-12">
                                <x-label for="old_password">Old password</x-label>
                                <x-input type="password" id="old_password" name="old_password" aria-describedby="old_password" :required="true" />
                                <x-input-error id="old_password"></x-input-error>
                            </x-input-parent>

                            <div class="row g-2">
                                <x-input-parent class="col-md-6">
                                    <x-label for="password">New password</x-label>
                                    <x-input type="password" id="password" name="password" aria-describedby="password" :required="false" />
                                    <x-input-error id="password"></x-input-error>
                                </x-input-parent>

                                <x-input-parent class="col-md-6">
                                    <x-label for="password_confirmation">Confirm new password</x-label>
                                    <x-input type="password" id="password_confirmation" name="password_confirmation" aria-describedby="password_confirmation" :required="false" />
                                    <x-input-error id="password_confirmation"></x-input-error>
                                </x-input-parent>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-center align-items-center flex-grow-1">
                        <a type="button" class="text-decoration-none text-dark" data-bs-dismiss="modal">Close</a>
                    </div>
                    <button type="submit" class="btn btn-primary flex-grow-1" form="user_update">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <button type="button" class="btn-close d-none" data-bs-dismiss="toast" aria-label="Close"></button>
            <div class="toast-body">
                Minimum of one breakdown item.
            </div>
        </div>
    </div>

    @if (session('success') || session('error'))
    <div class="toast align-items-center text-bg-{{ session('success') ? "success" : "danger" }} position-fixed border-0 fade bottom-0 end-0 m-5 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
        <div class="toast-body">
            {{ session('success') }}
            {{ session('error') }}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif

</x-layout>

