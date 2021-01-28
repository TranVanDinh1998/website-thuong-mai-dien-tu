@extends('admin.layout')
@section('title', 'Import product')
@section('content')
    <!--main content start-->
    {{-- <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="#">Product management</a></li>
                <li class="active">Import</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Import
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form role="form" method="POST" id="import_product_form"
                            action="{{ URL::to('/administrator/product/import') }}" enctype="multipart/form-data">
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label for="number">Number of import</label>
                                <input type="number" name="number" id="import_number" class="form-control"
                                    placeholder="Number of import">
                            </div>
                            <div id="import_section">

                            </div>
                            <button type="submit" class="btn btn-info">Submit</button>
                        </form>
                    </div>
                </div>
            </section>
        </section>
        <!-- footer -->
        @include('admin.footer')
        <!-- / footer -->
    </section>

    <!--main content end-->
    <script>
        $(document).ready(function() {
            $('#import_number').keyup(function() {
                var number = $('#import_number').val();
                var html = '';
                for (var count = 0; count < number; count++) {
                    html += '<div class="form-group">';
                    html += '          <div class="row">';
                    html += '               <div class="col-sm-8">';
                    html += '                   <label for="producer_id">Product:</label>';
                    html += '                  <select class="form-control" name="product_id_' + count +
                        '"required>';
                    html += '                       <option>Select product </option>';
                    html +=
                        '                       @foreach ($products as $product)';
                    html +=
                        '                           <option value="{{ $product->id }}">{{ $product->id }} -';
                    html += '                               {{ $product->name }}';
                    html += '                           </option>';
                    html += '                        @endforeach';
                    html += '                    </select>';
                    html += '               </div>';
                    html += '               <div class="col-sm-4">';
                    html += '                   <label for="quantity">Quantity</label>';
                    html +=
                        '                   <input type="number" class="form-control" min="0" name = "quantity_' +
                        count + '"';
                    html += '                       placeholder="quantity">';
                    html += '               </div>';
                    html += '           </div>';
                    html += '       </div>';
                }
                $('#import_section').html(html);
            });
            $('#import_product_form').submit(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('administrator/product/import') }}",
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        if (response.error == true) {
                            alert("Error occoured.");
                        } else {
                            alert("Imported.");
                            window.location.href = "{{ url('administrator/product') }}";
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }

                });

            });
        });

    </script> --}}
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.product.index') }}">Product management</a></li>
                <li class="active">Import</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Import
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form role="form" method="POST" id="import_product_form"
                            action="{{ route('admin.product.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div id="errorMessage"></div>
                            {{-- <div class="form-group"> --}}
                                <input type="hidden" name="number" value="{{ $count }}">
                                {{-- @foreach ($products as $product) --}}
                                @for($i = 0; $i < $count; $i++)
                                <div class="row form-group panel">
                                    <div class="col-sm-6">
                                        <label>Product #{{ $products[$i]->id }}</label>
                                        @if ($products[$i]->image)
                                            <img src="{{ URL::to('uploads/products-images/' . $products[$i]->id . '/' . $products[$i]->image) }}"
                                                style="width: 100%;height:auto;">
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="producer_id">Name : {{ $products[$i]->name }}</label>
                                            <input type="hidden" class="form-control" value="{{ $products[$i]->id }}"
                                                name="import[{{$i}}][product_id]">
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" class="form-control" min="0"
                                                name="import[{{$i}}][quantity]" placeholder="quantity">
                                        </div>
                                    </div>
                                </div>
                                @endfor

                                {{-- @endforeach --}}
                                {{--
                            </div> --}}
                            <button type="submit" class="btn btn-info">Submit</button>
                        </form>
                    </div>
                </div>
            </section>
        </section>
        <!-- footer -->
        @include('admin.footer')
        <!-- / footer -->
    </section>

    <!--main content end-->
    <script>
        $('#import_product_form').submit(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.product.import') }}",
                type: 'POST',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                beforeSend: function() {},
                success: function(response) {
                    console.log(response);
                    if (response.error == true) {
                        alert("Error occoured.");
                    } else {
                        alert("Imported.");
                        window.location.href = "{{ url('administrator/product') }}";
                    }
                },
                error: function(e) {
                    console.log(e);
                }
            });
        });

    </script>
@endsection
