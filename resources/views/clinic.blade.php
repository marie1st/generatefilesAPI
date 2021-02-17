
<div class="container">
    <div>
        <h2>TEST</h2>
        <form action="{{action ('ClinicController@store')}}" method="post" enctype="multipart/form-data">
        @csrf
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif

          @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
            <div class = "form-group">
                <label>Clinic Name</label>
                <input type ="text" name="clinic_name" class="form-control" placeholder="Clinic Name">
            </div>
            <br>
            <div class = "form-group">
                <label>Phone</label>
                <input type ="text" name="phone" class="form-control" placeholder="Phone">
                </div>
            <br>
            <div class = "form-group">
                <label>EMAIL</label>
                <input type ="text" name="email" class="form-control" placeholder="name@name.com">
            </div>
            <br>
            <div class = "form-group">
                <label>Address</label>
                <input type ="text" name="address" class="form-control" placeholder="Address">
            </div>
            <br>
            <div class="form-group">
                <label for="clinic_file1">Company Registration File#1:</label>
                <input type="file" class="form-control-file" id="clinic_file1" name="clinic_file1">
            </div>
            <br>
         
            <div class = "form-group">
                <label>Country</label>
                <input type ="text" name="country" class="form-control" placeholder="country">
            </div>
            <br>

			<div class = "form-group">
                <label>Clinic Registration Number</label>
                <input type ="text" name="clinic_registration_number" class="form-control" placeholder="clinic_registration_number">
            </div>
            <br>
         

            <div class = "form-group">
                <button type="submit"  class="btn btn-primary btn-block mt-4">
                Update Info
                </button>

            <div>
        </form>
     

    </div>
</div>
