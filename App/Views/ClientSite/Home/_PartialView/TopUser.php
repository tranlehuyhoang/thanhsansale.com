<style>
    .description-top h1 h2 h3 h4 h5 h6 {
        color: #ffd700;
        text-shadow: 0px 0px 10px rgba(255, 215, 0, 0.7);
    }
</style>
<div class="col-lg-12 mt-4 card"
    style="padding: 10px;box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;background-color: antiquewhite;">
    <!-- <h4 style="color: #ffd700;text-shadow: 0px 0px 10px rgba(255, 215, 0, 0.7);">Bảng Vàng Tháng <?= date('m') ?></h4>
    <span style="font-size: 16px;">
        Sự kiện diễn ra từ ngày <strong>1/<?= date('m/Y') ?></strong> đến ngày
        <strong><?= date('t') ?>/<?= date('m/Y') ?></strong>
    </span>
    <h4 style="color: #ffd700;text-shadow: 0px 0px 10px rgba(255, 215, 0, 0.7);">Cơ cấu giải thưởng</h4>
    <ul>
        <li>
            <i class="icofont-arrow-right" style="color: orange;"></i>
            <span style="font-size: 16px;">Giải nhất: 1.000.000 VNĐ</span>
        </li>
        <li>
            <i class="icofont-arrow-right" style="color: orange;"></i>
            <span style="font-size: 16px;">Giải nhì: 500.000 VNĐ</span>
        </li>
        <li>
            <i class="icofont-arrow-right" style="color: orange;"></i>
            <span style="font-size: 16px;">Giải ba: 300.000 VNĐ</span>
        </li>
        <li>
            <i class="icofont-arrow-right" style="color: orange;"></i>
            <span style="font-size: 16px;">Giải khuyến khích: 100.000 VNĐ</span>
        </li>
    </ul> -->
    <div class="description-top">
        <?= $settingClient->DescriptionTop ?>
    </div>

    <!-- table -->
    <table class="table table-hover mt-2">
        <thead>
            <tr>
                <th>Top</th>
                <th>ID</th>
                <th>Username</th>
                <th>Tiền hoàn trong tháng này</th>
            </tr>
        </thead>
        <tbody>
            <?php $index=1;  foreach($topUsers as $item ): ?>
                <tr>
                    <td <?php if($index==1) echo "style='font-weight: bold;color: red;'"; if($index==2) echo "style='font-weight: bold;color: red;'"  ?>>
                        <?=$index++?>
                    </td>
                    <td><?=$item->Id?></td>
                    <td><?=$item->Username?></td>
                    <td><?=$item->Money?></td>
                </tr>
                
            <?php endforeach; ?>
        </tbody>
    </table>
</div>