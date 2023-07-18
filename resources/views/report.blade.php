@extends('layout')
@section('section')
    <section class="section" id="authenticated-section" style="display: none;">
        <div class="section row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted">Start Date</label>
                                <input type="date" class="form-control" id="start_date">
                                <span class="text-danger" id="error_start_date"></span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted">End Date</label>
                                <input type="date" class="form-control" id="end_date">
                                <span class="text-danger" id="error_end_date"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted">Movement Type</label>
                                <select id="transaction_type" class="choices form-select">
                                    <option value="0">All</option>
                                    <option value="1">In</option>
                                    <option value="2">Out</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">Item</label>
                                <select id="item_id" class="choices form-select">
                                    <option value="0">All Item</option>
                                    @foreach ($item as $i)
                                    <option value="{{ $i->id }}">{{ $i->item_code }} - {{ $i->item_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 text-end">
                                <button class="btn btn-primary" id="filterBtn">Filter</button>
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
                        <table class="table" id="tableList">
                            <thead>
                                <th>Transaction Date</th>
                                <th>Transaction ID</th>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Qty</th>
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
    <script src="/ownassets/js/report.js"></script>
@endsection
