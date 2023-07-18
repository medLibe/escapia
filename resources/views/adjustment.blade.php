@extends('layout')
@section('section')
    <section class="section" id="authenticated-section" style="display: none;">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-8">
                                <div class="card-header">
                                    <h4 class="card-title text-center">Adjust Your Item Here</h4>
                                </div>
                                <div class="form-group mb-3">
                                    <input type="hidden" class="form-control" id="_token" readonly value="{{ csrf_token() }}">
                                    <label for="transaction_date" class="form-label">Transaction Date <b
                                            class="text-danger">*</b></label>
                                    <input type="date" class="form-control" id="transaction_date"
                                        value="{{ date('Y-m-d') }}">
                                    <div class="text-danger" id="error_transaction_date"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="transaction_no" class="form-label">Transaction ID <b
                                            class="text-danger">*</b></label>
                                    <input type="text" style="background-color: #eeefef; color: #06063c;" readonly
                                        class="form-control" id="transaction_no" value="{{ $transaction_id }}">
                                        <div class="text-danger" id="error_transaction_no"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="transaction_type" class="form-label">Movement Type <b
                                            class="text-danger">*</b></label>
                                    <select id="transaction_type" class="choices form-select">
                                        <option value="1">In</option>
                                        <option value="2">Out</option>
                                    </select>
                                    <div class="text-danger" id="error_transaction_type"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="item_id" class="form-label">Item <b class="text-danger">*</b></label>
                                    <select id="item_id" class="choices form-select">
                                        @foreach ($item as $i)
                                            <option value="{{ $i->id }}">{{ $i->item_code }} - {{ $i->item_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger" id="error_item_id"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="qty_stock" class="form-label">Qty Item Stock <b
                                            class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <label class="input-group-text"><i class="fa-solid fa-box-open"></i></label>
                                        <input type="text" style="background-color: #eeefef; color: #06063c;" readonly
                                        class="form-control" id="qty_stock">
                                    </div>
                                    <div class="text-danger" id="error_qty_stock"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="qty_adjust" class="form-label">Qty Item Adjust <b
                                            class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <label class="input-group-text"><i class="fa-solid fa-edit"></i></label>
                                        <input type="text" class="form-control numeric" id="qty_adjust">
                                    </div>
                                    <div class="text-danger" id="error_qty_adjust"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description <sup
                                            class="text-muted">(Optional)</sup></label>
                                    <textarea id="description" cols="20" rows="3" class="form-control"></textarea>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary" id="btnAdjust">Adjust</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-striped" id="tableAdjust">
                            <thead>
                                <th>Transaction Date</th>
                                <th>Transaction ID</th>
                                <th>Movement Type</th>
                                <th>Item</th>
                                <th>Qty Adjust</th>
                                <th>Description</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="unauthenticated-section" style="display: none;">
        <div class="row-justify-content-center mt-3">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted fs-4">Silahkan login terlebih dahulu.</p>
                </div>
            </div>
        </div>
    </section>

    <script src="/ownassets/js/adjustment.js"></script>
@endsection
