<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="optional_add_category"
    class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">New category</h4>
            </div>
            <div class="modal-body">

                <form role="form">
                    <div class="form-group">
                        <label for="category_name">Name</label>
                        <input type="text" class="form-control" id="category_name" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="category_description">Description</label>
                        <textarea type="text" class="form-control" id="category_description"
                            placeholder="Enter email"> </textarea>
                        <script>
                            CKEDITOR.replace('category_description');

                        </script>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control" id="sub1_image_selected" name="image"
                            placeholder="Select image" required>
                        <p class="help-block">Only accept file .jpg, .png, .gif and
                            < 5MB</p>
                                <img id="sub1_image_tag" width="200px" height="auto;" class="img-responsive" src="">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>

</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="optional_add_producer"
    class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">New producer</h4>
            </div>
            <div class="modal-body">

                <form role="form">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="email" class="form-control" id="exampleInputEmail3" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control" id="sub2_image_selected" name="image"
                            placeholder="Select image" required>
                        <p class="help-block">Only accept file .jpg, .png, .gif and
                            < 5MB</p>
                                <img id="sub2_image_tag" width="200px" height="auto;" class="img-responsive" src="">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#sub1_image_tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#sub1_image_selected").change(function() {
            readURL1(this);
        });

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#sub2_image_tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#sub2_image_selected").change(function() {
            readURL2(this);
        });
    });

</script>
