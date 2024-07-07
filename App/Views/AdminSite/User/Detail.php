<div class="row">
    <!-- Detail user -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail User - <?=$slug?></h5>
            <p class="card-text">Id: <?= $user->Id ?></p>
            <p class="card-text">Username: <?= $user->Username ?></p>
            <p class="card-text">Email: <?= $user->Email ?></p>
            <p class="card-text">Fullname: <?= $user->FullName ?></p>
            <p class="card-text">Role: <?= $user->Role ?></p>
            <a href="/user" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>