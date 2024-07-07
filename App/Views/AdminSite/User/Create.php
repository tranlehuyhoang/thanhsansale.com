<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Thêm Mới Tài Khoản</div>
            <div class="card-body">
                <form method="post" action="<?=ADMIN_PATH?>/user/create" class="row">
                    <div class="form-group col-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="Username" placeholder="Enter username">
                    </div>
                    <div class="form-group col-6">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" name="Email" placeholder="Enter email">
                    </div>
                    <div class="form-group col-6">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" id="fullname" name="FullName" placeholder="Enter fullname">
                    </div>
                    <div class="form-group col-6">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" id="password" name="Password" placeholder="Enter password">
                    </div>
                    <!-- role -->
                    <div class="form-group col-6">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="Role">
                            <option value="0">Member</option>
                            <option value="1">Mod</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <a href="<?=ADMIN_PATH?>/user" class="btn btn-primary">Back</a>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>