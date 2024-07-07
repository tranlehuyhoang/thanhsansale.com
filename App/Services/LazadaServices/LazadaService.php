<?php

namespace App\Services\LazadaServices;

use App\Services\CategoryServices\CategoryService;
use App\Services\HmzHttp;
use App\Services\LazadaServices\ILazadaService;
use DateTime;
use Paulwscom\Lazada\LazopClient;
use Paulwscom\Lazada\LazopRequest;
use Paulwscom\Lazada\UrlConstants;

class LazadaService implements ILazadaService
{

    private CategoryService $categoryService;
    private $appKey;
    private $appSecret;
    private $userToken;
    public $category;
    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->category = $this->categoryService->GetById(2);
        $this->appKey = '105827';
        $this->appSecret = 'r8ZMKhPxu1JZUCwTUBVMJiJnZKjhWeQF';
        //$this->userToken = 'e12f2bb55fd546e5a096a7cf7f859bac'; // muahanghoantien.com
        $this->userToken = '8e50825e862447a1915315658a1450d5'; // thanhsansale.com


    }
    /**
     *
     * @param mixed $limit
     * @param mixed $page
     * @param mixed $offerId
     */
    public function GetOfferList($limit, $page, $offerId = 0)
    {
        $c = new LazopClient(UrlConstants::$api_gateway_url_vn, $this->appKey, $this->appSecret);
        $request = new LazopRequest('/marketing/offer/list/get', 'GET');
        $request->addApiParam('userToken', $this->userToken);
        $request->addApiParam('limit', $limit);
        $request->addApiParam('page', $page);
        if ($offerId != 0) {
            $request->addApiParam('offerId', $offerId);
        }
        $res = $c->execute($request);
        return $res;
    }
    /**
     *
     * @param mixed $offerType
     * 1 - Regular offer
     * 2 - MM offer
     * @param mixed $limit
     * @param mixed $page
     * @param mixed $categoryL1
     * @param mixed $mmCampaignId
     * @param mixed $productIds
     */
    public function GetProductFeed($offerType, $limit, $page, $categoryL1 = null, $mmCampaignId = null, $productIds = [])
    {
        $c = new LazopClient(UrlConstants::$api_gateway_url_vn, $this->appKey, $this->appSecret);
        $request = new LazopRequest('/marketing/product/feed', 'GET');
        $request->addApiParam('userToken', $this->userToken);
        $request->addApiParam('offerType', $offerType);
        $request->addApiParam('limit', $limit);
        $request->addApiParam('page', $page);
        if ($categoryL1 != null) {
            $request->addApiParam('categoryL1', $categoryL1);
        }
        if ($mmCampaignId != null) {
            $request->addApiParam('mmCampaignId', $mmCampaignId);
        }
        if (count($productIds) > 0) {
            $productIds = json_encode($productIds);
            $request->addApiParam('productIds', $productIds);
        }
        $res = $c->execute($request);
        return json_decode($res);
    }

    // API: /lazada/product-feed/id
    public function GetProductById($productIds)
    {
        $c = new LazopClient(UrlConstants::$api_gateway_url_vn, $this->appKey, $this->appSecret);
        $request = new LazopRequest('/marketing/product/feed', 'GET');
        $request->addApiParam('userToken', $this->userToken);
        $request->addApiParam('offerType', 1);
        $request->addApiParam('limit', 1);
        $request->addApiParam('page', 1);

        $productIds = json_encode($productIds);
        $request->addApiParam('productIds', $productIds);

        $res = $c->execute($request);
        return json_decode($res);
    }
    /**
     *
     * @param mixed $productId
     */
    public function TrackingLinkByProductId($productId, $mmCampaignId = null)
    {
        $c = new LazopClient(UrlConstants::$api_gateway_url_vn, $this->appKey, $this->appSecret);
        $request = new LazopRequest('/marketing/product/link', 'GET');
        $request->addApiParam('userToken', $this->userToken);
        $request->addApiParam('productId', $productId);
        if ($mmCampaignId != null) {
            $request->addApiParam('mmCampaignId', $mmCampaignId);
        }
        $res = $c->execute($request);
        return json_decode($res);
    }

    // get final url
    private function get_final_url($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);
        $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        // check link include https://s.lazada.vn/s.VVyVt => s.lazada.vn
        if (strpos($final_url, 's.lazada.vn') !== false) {
            // Assume $data contains the HTML content of the page
            // Define the pattern to extract the URL from the link rel tag
            $pattern = '/<link rel="origin" href="([^"]*?)"/';

            // Perform the regex match
            if (preg_match($pattern, $data, $matches)) {
                // Decode the extracted URL
                $linkRedirect = urldecode($matches[1]);

                // Get the final URL after redirections
                $final_url = $this->get_final_url($linkRedirect);
                curl_close($ch);
                return $final_url;
            }
        }

        curl_close($ch);
        return $final_url;
    }

    public function GetProductByLink($link)
    {
        //type1: https://www.lazada.vn/products/chuot-khong-day-logitech-pebble-m350-bao-hanh-12-thang-i1180362918-s6194286332.html?spm=a2o4n.officialstores.1001.djfy_6.82846780CXRens&&scm=1007.33792.323954.0&pvid=cb97682c-ef82-4a14-a6c4-a40170257be8&search=jfy&priceCompare=skuId%3A6194286332%3Bsource%3Atpp-recommend-plugin-23792%3Bsn%3Acb97682c-ef82-4a14-a6c4-a40170257be8%3BunionTrace%3A2141129417145560403414071e5977%3BoriginPrice%3A599000%3BvoucherPrice%3A599000%3BdisplayPrice%3A599000%3BsinglePromotionId%3A-1%3BsingleToolCode%3A-1%3BvoucherPricePlugin%3A1%3BbuyerId%3A1287142%3ButdId%3A-1%3Btimestamp%3A1714556040482
        // id: 1180362918
        //type2: https://www.lazada.vn/products/bo-san-pham-sua-chong-nang-la-roche-posay-cho-da-dau-mun-anthelios-uvmune400-fluid-50ml-i2563915699.html
        // id: 2563915699
        $final_url = $this->get_final_url($link);

        // Check if the link is from lazada.vn
        if (strpos($final_url, 'lazada.vn') === false) {
            return null;
        }

        // Regex patterns for different types of links
        $pattern1 = '/i(\d+)-s(\d+)\.html/';
        $pattern2 = '/i(\d+)\.html/';

        if (preg_match($pattern1, $final_url, $matches)) {
            $productId = [$matches[1]];
        } elseif (preg_match($pattern2, $final_url, $matches)) {
            $productId = [$matches[1]];
        } else {
            return null;
        }
        $res = $this->GetProductById($productId);
        return $res;
    }

    /**
     *
     * @param mixed $dateStart
     * @param mixed $dateEnd
     * @param mixed $limit
     * @param mixed $page
     * @param mixed $offerId
     * @param mixed $mmPartnerFlag
     */
    public function GetReports($dateStart, $dateEnd, $limit = 10, $page = 1, $offerId = 0, $mmPartnerFlag = false)
    {
        $c = new LazopClient(UrlConstants::$api_gateway_url_vn, $this->appKey, $this->appSecret);
        $request = new LazopRequest('/marketing/conversion/report', 'GET');
        $request->addApiParam('userToken', $this->userToken);

        // Assuming the input format is 'd/m/Y'
        $dateStart = DateTime::createFromFormat('d/m/Y', $dateStart)->format('Y-m-d');
        $dateEnd = DateTime::createFromFormat('d/m/Y', $dateEnd)->format('Y-m-d');

        $request->addApiParam('dateStart', $dateStart);
        $request->addApiParam('dateEnd', $dateEnd);
        $request->addApiParam('limit', (int) $limit);
        $request->addApiParam('page', (int) $page);

        if ($offerId !== 0) { // Check against strict inequality
            $request->addApiParam('offerId', $offerId);
        }

        // Convert boolean to string
        $request->addApiParam('mmPartnerFlag', $mmPartnerFlag ? 'true' : 'false');

        $res = $c->execute($request);
        return json_decode($res);
    }
}
