<?php
namespace App\Services\Common\Enums;


class EShopeeStatus {
    public const Pending = 1; // chờ xử lý
    public const Completed = 2; // hoàn thành
    public const Cancel = 3; // hủy
    public const NotPaid = 4; // chưa thanh toán

}
class ELazadaStatus
{
    public const Fulfilled = 1; // Trạng thái ghi nhận đơn
    public const Delivered = 2; // hoàn thành
    public const Cancel = 3; // hủy
}