@foreach ($products as $product)
<tr>
    <td>
        <label class="i-checks m-b-none">
            <input type="checkbox" name="post[]"><i></i>
        </label>
    </td>
    <td>
        {{ $product->id }}
    </td>
    <td>
        <img style="width: 70px; height : auto"
            src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
            alt="{{ $product->name }}">
    </td>
    <td><span class="text-ellipsis">{{ $product->name }}</span></td>
    <td><span class="text-ellipsis">{{ $product->create_date }}</span></td>
    <td><span class="text-ellipsis">{{ $product->price }}</span></td>
    <td><span class="text-ellipsis">{{ $product->remaining }}</span></td>
    <td>
        @foreach ($categories as $category)
            @if ($category->id == $product->category_id)
                {{ $category->name }}
            @endif
        @endforeach
    </td>
    <td>
        @foreach ($producers as $producer)
            @if ($producer->id == $product->producer_id)
                {{ $producer->name }}
            @endif
        @endforeach
    </td>
    <td>
        @if ($product->is_actived == 1)
            <div class="alert alert-success" role="alert">
                <strong>Active</strong>
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <strong>Inactive</strong>
            </div>
        @endif
    </td>
    <td>
        @if ($product->is_actived == 0)
            <a onclick="return confirm('Are you sure?')"
                href="{{ URL::to('administrator/product/activate/' . $product->id) }}"
                class="btn btn-success" title="Activate">
                <span class="glyphicon glyphicon-check"></span>
            </a>
        @else
            <a onclick="return confirm('Are you sure?')"
                href="{{ URL::to('administrator/product/deactivate/' . $product->id) }}"
                class="btn btn-warning" title="Deactivate">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
        @endif
    </td>
    <td>
        <a class="btn btn-info"
            href="{{ URL::to('administrator/product/edit/' . $product->id) }}">
            <span class="glyphicon glyphicon-edit"></span>
        </a>
    </td>
    <td>
        <a class="btn btn-default"
            href="{{ URL::to('administrator/product/' . $product->id . '/gallery') }}">
            <span class="glyphicon glyphicon-picture"></span>
        </a>
    </td>
    <td>
        <a onclick="return confirm('Are you sure?')"
            href="{{ URL::to('administrator/product/remove/' . $product->id) }}"
            class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>
        </a>
    </td>
</tr>
@endforeach