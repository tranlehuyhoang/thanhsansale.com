<div class="row">
    <div class="col-md-12">
        <h1>Thống kê</h1>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Top Hoàn tiền
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-nowrap">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên tài khoản</th>
                                <th>Email</th>
                                <th>Tên đầy đủ</th>
                                <th>Số TK</th>
                                <th>Tên Ngân Hàng</th>
                                <th>Số dư</th>
                            </tr>
                        </thead>
                        <tbody id="tableData">
                            <?php
                            $index = 1;
                            foreach ($users as $user) : ?>
                                <tr>

                                    <td><?= $index++ ?></td>
                                    <td><?= $user->Username; ?></td>
                                    <td><?= $user->Email; ?></td>
                                    <td><?= $user->FullName; ?></td>
                                    <td><?= $user->NumberBank; ?></td>
                                    <td title="<?= $user->NameBank; ?>" style="width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;">
                                        <?= $user->NameBank; ?>
                                    </td>

                                    <td class="text-success"><?= $user->Money; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <?= $pagination ?>
        </div>
    </div>
</div>