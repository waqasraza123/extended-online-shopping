{{ Form::open(['class' => 'form-horizontal', 'route' => 'user.profile.update', 'files' => true]) }}
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Image</label>

        <div class="col-sm-10">
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Name</label>

        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" id="inputName" placeholder="Name" value="{{$user->name}}">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-10">
            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" value="{{$user->email}}">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Phone</label>

        <div class="col-sm-10">
            <input type="text" name="phone" class="form-control" id="inputName" placeholder="Name" value="{{$user->phone}}">
        </div>
    </div>
    <div class="form-group">
        <label for="inputExperience" class="col-sm-2 control-label">User Name</label>

        <div class="col-sm-10">
            <input type="text" name="user_name" class="form-control" value="{{$user->email_phone}}" placeholder="Username">
        </div>
    </div>
    <div class="form-group">
        <label for="inputSkills" class="col-sm-2 control-label">About</label>

        <div class="col-sm-10">
            <textarea name="about" class="form-control" placeholder="We want to hear Something Interesting About you.">
                {{$user->about or ""}}
            </textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="inputSkills" class="col-sm-2 control-label">Change Password</label>
        <div class="col-sm-10">
            <input id="password" type="password" class="form-control" name="password"
                   placeholder="New Password">
        </div>
    </div>
    <div class="form-group">
        <label for="inputSkills" class="col-sm-2 control-label">Confirm New Password</label>
        <div class="col-sm-10">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                   placeholder="Enter Password Again">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </div>
{{ Form::close() }}