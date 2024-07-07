<style>
    /* Adjustments for images */
    .select2-results__option--highlighted[aria-selected] {
        background-color: #5897fb !important;
        color: white;
    }

    .img-flag {
        height: 30px;
        width: auto;
        margin-right: 10px;
        object-fit: cover;
        background-color: #dddddd;
        border-radius: 5px;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" async></script>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">Sửa Tài Khoản</div>
            <div class="card-body">
                <form method="post" action="<?= ADMIN_PATH ?>/user/edit/<?= $user->Id ?>" class="row">
                    <div class="form-group col-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="Username" placeholder="Enter username" value="<?= $user->Username ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" name="Email" placeholder="Enter email" value="<?= $user->Email ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="fullname">Fullname</label>
                        <input type="text" class="form-control" id="fullname" name="FullName" placeholder="Enter fullname" value="<?= $user->FullName ?>">
                    </div>
                    <div class="form-group col-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="Password" placeholder="Enter password">
                    </div>
                    <!-- Choose Role -->
                    <div class="form-group col-6">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="Role">
                            <option <?= ($user->Role == Member) ? 'selected' : '' ?> value="0">Member</option>
                            <option <?= ($user->Role == Mod) ? 'selected' : '' ?> value="1">Mod</option>
                            <option <?= ($user->Role == Admin) ? 'selected' : '' ?> value="2">Admin</option>
                        </select>
                    </div>
                    <!-- Name Bank -->
                    <label for="exampleInputNumber1">Chọn ngân hàng</label>
                    <div class="form-group col-md-12">
                        <select class="form-control" name="NameBank" id="NameBank" onchange="changeBank()">
                            <?php foreach ($banks as $bank) : ?>
                                <option data-image="<?= $bank->Logo ?>" <?= $bank->Code == $user->NameBank ? 'selected' : '' ?> value="<?= $bank->Code ?>">
                                    <?= $bank->Name ?>@<?= $bank->NameVPBank ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <img src="" alt="<?= $user->NameBank ?>" id="imageBank" class="img-fluid" style="max-width: 100px;">
                    <!-- Number Bank -->
                    <div class="form-group">
                        <label for="exampleInputNumber1">Số tài khoản ngân hàng</label>
                        <input type="text" class="form-control" name="NumberBank" id="NumberBank" value="<?= $user->NumberBank ?>" />
                    </div>

                    <div class="form-group mt-2">
                        <a href="<?= ADMIN_PATH ?>/user" class="btn btn-primary">Back</a>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // set image bank
        changeBank();

        function formatBank(bank) {
            if (!bank.id) {
                return bank.text;
            }
            let logo = $(bank.element).data('image');
            let name = bank.text.split('@');

            var $bank = $(
                `<span class="d-flex">
                    <img src="${logo}" class="img-flag" />
                    <div style="display: grid">
                        <span class="text-muted">${name[1]}</span>
                        <span>${name[0]}</span>
                    </div>
                </span>`
            );
            return $bank;
        };
        // selection template
        function formatBankSelection(bank) {
            let name = bank.text.split('@');
            return name[0];
        }

        // $('#NameBank').select2({
        //     templateResult: formatBank,
        //     templateSelection: formatBankSelection
        // });

    });


    function changeBank() {

        let logo = $('#NameBank').find(':selected').data('image');
        $('#imageBank').attr('src', logo);
    }
</script>