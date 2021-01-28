@extends('admin.layout')
@section('title', 'Review management ')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.review.index') }}">Review management</a></li>
                <li class="active">Review #{{ $review->id }}</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Review #{{ $review->id }}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="hidden" class="form-control" name="id"
                                    value="{{ $review->id }}">
                                <input type="text" class="form-control" name="name" placeholder="name"
                                    value="{{ $review_user->name }}">
                            </div>
                            <div class="form-group">
                                <label for="price">Email</label>
                                <input type="email" class="form-control" value="{{ $review_user->email }}" name="email"
                                    placeholder="email">
                            </div>
                            <div class="form-group">
                                <label for="discount">Create at</label>
                                <input type="date" class="form-control" name="discount"
                                    placeholder="create date" value="{{ $review->create_date }}">
                            </div>
                            <div class="form-group">
                                <label for="discount">Price rate</label>
                                <input type="number" class="form-control" name="discount"
                                    placeholder="create date" value="{{ $review->price_rate }}">
                            </div>
                            <div class="form-group">
                                <label for="discount">Quality rate</label>
                                <input type="number" class="form-control" name="discount"
                                    placeholder="create date" value="{{ $review->quality_rate }}">
                            </div>
                            <div class="form-group">
                                <label for="discount">Value rate</label>
                                <input type="number" class="form-control" name="discount"
                                    placeholder="create date" value="{{ $review->value_rate }}">
                            </div>
                            <div class="form-group">
                                <label for="discount">Summary</label>
                                <input type="text" class="form-control" name="discount"
                                    placeholder="create date" value="{{ $review->summary }}">
                            </div>
                            <div class="form-group">
                                <label for="">Comment</label>
                                <textarea type="text" class="form-control" id="descript" name="description"
                                    placeholder="Comment">{{ $review->description }}</textarea>
                                <script>
                                    CKEDITOR.replace('descript');
                                </script>
                            </div>
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

@endsection
