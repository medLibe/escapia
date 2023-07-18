@extends('layout')
@section('section')
    <section class="section row justify-content-center" id="authenticated-section" style="display: none;">
        <div class="col-12 my-3">
            <input type="hidden" class="form-control" id="_token" value="{{ csrf_token() }}" readonly>
            <button class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#createModal"><i
                    class="fa-solid fa-plus"></i> Add Item</button>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal"><i
                    class="fa-solid fa-file-import"></i> Import From Excel</button>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table" id="tableList">
                        <thead>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Packaging</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Import --}}
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Import Item From Excel</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group mb-3">
                                <label for="importFile" class="form-label">Choose File Excel</label>
                                <input type="file" class="form-control" id="importFile">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnImport">Import</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Create --}}
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add New Item</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group mb-3">
                                <label for="item_code" class="form-label">Item Code <b class="text-danger">*</b></label>
                                <input type="text" class="form-control" placeholder="Item Code"
                                    value="{{ $item_code }}" readonly id="item_code">
                                <span class="text-danger" id="error_item_code"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="item_code" class="form-label">Item Name <b class="text-danger">*</b></label>
                                <input type="text" class="form-control" placeholder="Item Name" id="item_name">
                                <span class="text-danger" id="error_item_name"></span>
                            </div>
                            {{-- <div class="form-group mb-3">
                            <label for="item_code" class="form-label">Unit Type <b class="text-danger">*</b></label>
                            <input type="text" class="form-control" placeholder="Unit Type" readonly value="Pcs">
                        </div> --}}
                            <div class="form-group mb-3">
                                <label for="item_code" class="form-label">Packaging <b class="text-danger">*</b></label>
                                <input type="text" class="form-control" placeholder="Packaging" id="packaging">
                                <span class="text-danger" id="error_packaging"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="item_code" class="form-label">Qty Per Packaging <b
                                        class="text-danger">*</b></label>
                                <input type="text" class="numeric form-control" placeholder="Qty Per Packaging"
                                    id="qty_per_packaging">
                                <span class="text-danger" id="error_qty_per_packaging"></span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnCreate">Save</button>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($item as $i)
            {{-- Modal Edit --}}
            <div class="modal fade" id="editModal{{ $i->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Item</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group mb-3">
                                    <label for="item_code" class="form-label">Item Code</label>
                                    <input type="text" class="form-control" placeholder="Item Code"
                                        value="{{ $item_code }}" readonly
                                        style="background-color: #eeefef; color: #06063c;">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="item_code" class="form-label">Item Name</label>
                                    <input type="text" class="form-control" placeholder="Item Name"
                                        id="item_name{{ $i->id }}" value="{{ $i->item_name }}">
                                </div>
                                {{-- <div class="form-group mb-3">
                            <label for="item_code" class="form-label">Unit Type</label>
                            <input type="text" class="form-control" placeholder="Unit Type" readonly value="Pcs">
                        </div> --}}
                                <div class="form-group mb-3">
                                    <label for="item_code" class="form-label">Packaging</label>
                                    <input type="text" class="form-control" placeholder="Packaging"
                                        id="packaging{{ $i->id }}" value="{{ $i->packaging }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="item_code" class="form-label">Qty Per Packaging</label>
                                    <input type="text" class="numeric form-control" placeholder="Qty Per Packaging"
                                        id="qty_per_packaging{{ $i->id }}" value="{{ $i->qty_per_packaging }}">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-warning btnUpdate"
                                id="btn{{ $i->id }}">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Delete --}}
            <div class="modal fade" id="deleteModal{{ $i->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Item</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure want to delete <strong>{{ $i->item_name }}</strong>?
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger btnDelete"
                                id="btn{{ $i->id }}">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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

    <script src="/ownassets/js/item.js"></script>
@endsection
