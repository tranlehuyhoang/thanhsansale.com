<?php
namespace App\Services\LazadaServices;

interface ILazadaService
{
    public function GetOfferList($limit, $page, $offerId = 0);

    public function GetProductFeed($offerType, $limit, $page, $categoryL1 = null, $mmCampaignId = null, $productIds = []);
    public function GetProductById($productId);
    public function TrackingLinkByProductId($productId, $mmCampaignId = null);

    public function GetProductByLink($link);

    public function GetReports($dateStart, $dateEnd, $limit = 10, $page = 1, $offerId = 0, $mmPartnerFlag = false);
}