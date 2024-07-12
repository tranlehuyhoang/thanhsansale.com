<?php

namespace App\Services\Identities\UserServices;

use App\Models\User;
use App\Services\BankServices\BankService;
use App\Services\BaseService;
use App\Services\Common\ExcelHelper;
use App\Services\Common\Helper;
use App\Services\Common\SqlCommon;
use App\Services\MailServices\MailService;
use stdClass;

class UserService extends BaseService implements IUserService
{
    public $tableName = 'users';

    private MailService $mailService;
    private BankService $bankService;
    public function __construct()
    {
        $this->mailService = new MailService();
        $this->bankService = new BankService();
        parent::__construct();
    }
    /**
     * @param mixed $username
     * @return mixed
     */
    public function GetByUsername($username)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Username = '$username'", NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if ($data == null) {
            return null;
        }
        $user = new User($data);
        return $user;
    }

    /**
     *
     * @param mixed $email
     * @return mixed
     */
    public function GetByEmail($email)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Email = '$email'", NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if ($data) {
            $user = new User($data);
            return $user;
        }
        $user = null;
        return $user;
    }
    public function GetByEmailOrUsername($email, $username)
    {
        // Xây dựng truy vấn SQL để tìm kiếm theo email hoặc username
        $buildSql = SqlCommon::BuildQuery(
            $this->tableName,
            "(Email = '$email' OR Username = '$username')",
            NULL,
            NULL,
            NULL
        );

        // Thực hiện truy vấn và lấy dữ liệu
        $data = $this->context->fetch_one($buildSql);

        // Kiểm tra nếu có dữ liệu, khởi tạo đối tượng User và trả về
        if ($data) {
            $user = new User($data);
            return $user;
        }

        // Nếu không có dữ liệu, trả về null
        $user = null;
        return $user;
    }

    /**
     * @return mixed
     */
    public function GetAll()
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, NULL, NULL, NULL, NULL);
        $data = $this->context->fetch($buildSql);
        $users = [];
        foreach ($data as $item) {
            $user = new User($item);
            array_push($users, $user);
        }
        return $users;
    }

    /**
     *
     * @param mixed $paginate
     * @return mixed
     */

    public function GetWithPaginate($pageIndex, $pageSize, $filter = [], $orderBy = NULL, $between = NULL)
    {
        $offset = ($pageIndex - 1) * $pageSize;
        $whereSql = '';
        if (!empty($filter)) {
            $filterParts = [];
            foreach ($filter as $key => $value) {
                // check is boolean
                if (is_bool($value)) {
                    $value = $value ? 1 : 0;
                    $filterParts[] = "$key = $value";
                }
                // check value is int 
                else if (is_numeric($value)) {
                    $filterParts[] = "$key >= $value";
                } else
                    $filterParts[] = "$key LIKE '%$value%'";
            }
            $whereSql = 'WHERE ' . implode(' AND ', $filterParts);
            if ($between) {
                $whereSql .= " AND $between";
            }
        }
        $orderBy = $orderBy ?? 'Money DESC';
        $buildSql = "
			SELECT * FROM $this->tableName
			$whereSql
			ORDER BY $orderBy 
			LIMIT $offset, $pageSize
		";
        $data = $this->context->fetch($buildSql);
        $users = [];
        foreach ($data as $item) {
            $bank = $this->bankService->GetByCode($item['NameBank']);
            $item['NameBank'] = $bank->Name ?? '';
            $user = new User($item);
            $user->BankCode = $bank->Code ?? '';
            array_push($users, $user);
        }
        return $users;
    }

    /**
     *
     * @param mixed $id
     * @return mixed
     */
    public function GetById($id)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "Id = '$id'", NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);
        if ($data == null) {
            return null;
        }
        $user = new User($data);
        return $user;
    }

    /**
     * @param mixed $username
     * @param mixed $password
     * @return mixed
     */
    public function Login($usernameOrEmail, $password)
    {
        $buildSql = SqlCommon::BuildQuery($this->tableName, "(Username = '$usernameOrEmail' OR Email = '$usernameOrEmail')", NULL, NULL, NULL);
        $data = $this->context->fetch_one($buildSql);

        if ($data == null) {
            return null;
        }
        $hashedPasswordFromDatabase = $data['Password'];
        if (Helper::VerifyPassword($password, $hashedPasswordFromDatabase)) {
            $user = new User($data);
            return $user;
        }
        return null;
    }



    /**
     *
     * @param mixed $user
     * @return mixed
     */
    public function Register($user)
    {
        $user['Password'] = Helper::HashBcrypt($user['Password']);

        $user['CreatedAt'] = date('Y-m-d H:i:s');
        $user['CreatedBy'] = $user['CreatedBy'] ?? 'Admin';
        $user['IsActive'] = $user['IsActive'] ?? 1;
        $user['Role'] = $user['Role'] ?? 0;

        $sql = SqlCommon::INSERT($user, $this->tableName);
        return $this->context->query($sql);
    }

    /**
     *
     * @param mixed $userId
     * @param mixed $password
     */
    public function UpdatePassword($userId, $password)
    {

        $data['Password'] = Helper::HashBcrypt($password);
        $sql = SqlCommon::UPDATE($this->tableName, $data, $userId);
        return $this->context->query($sql);
    }
    /**
     * @param mixed $items => [
     *      'UserId' => 1,
     *      'Price' => 1000,
     * ]
     */
    public function AddMoney($items)
    {
        // Extract user IDs from the $items array
        $userIds = array_column($items, 'UserId');

        // Check and get user information
        $users = $this->GetByIds($this->tableName, $userIds);

        if ($users && count($users) > 0) {
            $sql = '';
            foreach ($items as $item) {
                // check item['UserId] in $users
                if (array_search($item['UserId'], array_column($users, 'Id')) !== false) {
                    $money = $item['Price'] ?? 0; // Get money from items array
                    $userId = $item['UserId'];
                    $sql .= "
						UPDATE $this->tableName
						SET Money = Money + $money
						WHERE Id = $userId;
					";
                }
            }
            if (empty($sql)) {
                return false;
            }

            // send mail
            // $recipients = array_column($users, 'Email');
            // $body = "
            // 	<span>Bạn vừa được cộng tiền vào tài khoản</span>
            // 	<p>Vui lòng kiểm tra tài khoản của bạn</p>
            // ";
            // $mailQuery = new MailQuery(Null, $recipients, 'Trả tiền đơn hàng', $body, []);
            // $res = $this->mailService->SendMail($mailQuery);

            return $this->context->closeCursor($sql);
        }
        return false;
    }

    /**
     *
     * @param mixed $items
     */
    public function SubtractMoney($items)
    {
        // Extract user IDs from the $items array
        $userIds = array_column($items, 'UserId');

        // Check and get user information
        $users = $this->GetByIds($this->tableName, $userIds);

        if ($users && count($users) > 0) {
            $sql = '';
            foreach ($users as $user) {
                $money = (float) $items[$user['Id']]['Price'] ?? 0; // Get money from items array
                $user['Money'] -= $money;
                $sql .= SqlCommon::UPDATE($this->tableName, $user, $user['Id']) . ';';
            }
            return $this->context->query($sql);
        }
        return false;
    }

    public function ResetAllMoney($userId = NULL)
    {
        if ($userId) {
            // If a specific userId is provided, update only that user's Money to 0
            $sql = "UPDATE $this->tableName SET Money = 0 WHERE Id = $userId";
            $stmt = $this->context->query($sql);
            return $stmt;
        } else {
            // If no userId is provided, update Money to 0 for users meeting the specified conditions
            $sql = "UPDATE $this->tableName AS u
					JOIN banks AS b ON u.NameBank = b.Code
					SET u.Money = 0
					WHERE u.Money >= 10000 
					AND b.Name IS NOT NULL 
					AND u.NumberBank IS NOT NULL 
					AND u.FullName IS NOT NULL";
            return $this->context->query($sql);
        }
    }

    public function AddMoneyByUser($userId, $money = 0)
    {
        $sql = "UPDATE $this->tableName
				SET Money = Money + $money
				WHERE Id = $userId;
			";
        return $this->context->query($sql);
    }

    // build SQL diff Money by UserId
    public function BuildResetMoneySql($userId)
    {
        $sql = "UPDATE $this->tableName
				SET Money = 0,
				WHERE Id = $userId;
			";
        return $sql;
    }
    /**
     *
     * @param mixed $filter
     */
    public function ExportExcel($filter = [])
    {
        $users = $this->GetWithPaginate(1, 10000, $filter, "Money DESC");
        $data = [];
        foreach ($users as $user) {
            array_push($data, [
                'Id' => $user->Id,
                'Username' => $user->Username ?? '',
                'Email' => $user->Email ?? '',
                'FullName' => $user->FullName ?? '',
                'PhoneNumber' => $user->Phone ?? '',
                'Money' => $user->Price ?? 0,
                'BankNumber' => $user->NumberBank ?? '',
                'BankName' => $user->NameBank ?? '',
            ]);
        }
        // get dictionary
        $templatePath = $this->rootPath . '/Resources/Templates/DanhSachTaiKhoan.xlsx';
        $outputPath = $this->rootPath . '/Resources/Exports/DanhSachTaiKhoan_' . time() . '.xlsx';
        $file = ExcelHelper::exportToExcelWithTemplate($data, $templatePath, $outputPath);
        return $file;
    }
    /**
     *
     * @param mixed $filter
     */
    public function ExportVPBankExcel($filter = [])
    {
        $between = "Money >= 10000 AND NameBank IS NOT NULL AND NumberBank IS NOT NULL AND FullName IS NOT NULL";
        $users = $this->GetWithPaginate(1, 100000, $filter, "Money DESC", $between);
        $data = [];
        $index = 1;
        $i = 1;
        $sql = "";

        foreach ($users as $user) {
            if ($user->Price <= 0 && $user->NumberBank != null)
                continue;

            $bank  = $this->bankService->GetByCode($user->BankCode);
            if ($bank == null) {
                continue;
            }
            $i++;
            $fullName = strtoupper(Helper::remove_vietnamese_diacritics($user->FullName ?? ''));
            $BankId = "=IF(ISNA(VLOOKUP(E$i,Banks!\$C\$2:\$D\$61,2,0)),\"\",VLOOKUP(E$i,Banks!\$C\$2:\$D\$61,2,0))";

            array_push($data, [
                'Index' => $index++,
                'BankNumber' => $user->NumberBank ?? '',
                'FullName' => $fullName,
                'Money' => $user->Price ?? 0,
                'BankName' => $bank->NameVPBank ?? '',
                'BankId' => $BankId ?? '',
                'Note' => 'Thanh toan tien khach hang ' . $user->Username ?? '',
            ]);
            $sql .= $this->BuildResetMoneySql($user->Id);
        }
        if (empty($data)) {
            return null;
        }
        $templatePath = $this->rootPath . '/Resources/Templates/DanhSachTaiKhoanVPBank.xls';
        $outputPath = $this->rootPath . '/Resources/Exports/DanhSachTaiKhoanVPBank_' . time() . '.xls';
        $file = ExcelHelper::exportToExcelWithTemplate($data, $templatePath, $outputPath, 2);
        // $this->context->query($sql);

        return $file;
    }
    /**
     *
     * @param mixed $filter
     */
    public function ExportBIDVExcel($filter = [])
    {
        $between = "Money >= 10000 AND NameBank IS NOT NULL AND NumberBank IS NOT NULL AND FullName IS NOT NULL";
        $users = $this->GetWithPaginate(1, 100000, $filter, "Money DESC", $between);
        $data = [];
        $index = 1;
        $sql = "";
        foreach ($users as $user) {
            if ($user->Price <= 0)
                continue;

            $bank = $this->bankService->GetByCode($user->BankCode);
            if ($bank == null) {
                continue;
            }
            $fullName = strtoupper(Helper::remove_vietnamese_diacritics($user->FullName ?? ''));
            // convert to string
            $cardNumber =  strval($user->NumberBank ?? '');
            // number to string 1 => 0001
            array_push($data, [
                'Index' =>  $index++,
                'RefCode' => '',
                'CardNumber' => $cardNumber,
                'Price' => $user->Price ?? 0,
                'Currency' => 'VND',
                'TaxType' => 'I',
                'Name' => $fullName,
                'Indentity' => '',
                'CMTNumber' => '',
                'CMTDate' => '',
                'CMTPlace' => '',
                'MethodTransfer' => 3.1,
                'BankName' => $bank->NameVPBank ?? '',
                'BankLocation' => '',
                'Note' => 'Thanh toan tien khach hang ' . $user->Username ?? '',
                'Date' => date('d/m/Y'),
            ]);
            $sql .= $this->BuildResetMoneySql($user->Id);
        }
        if (empty($data)) {
            return null;
        }
        $templatePath = $this->rootPath . '/Resources/Templates/DanhSachTaiKhoanBIDV.xlsx';
        $outputPath = $this->rootPath . '/Resources/Exports/DanhSachTaiKhoanBIDV_' . time() . '.xlsx';
        $file = ExcelHelper::exportToExcelWithTemplate($data, $templatePath, $outputPath, 2);
        // $res = $this->context->query($sql);
        return $file;
    }

    /**
     *
     * @param mixed $filter
     */
    public function ExportTCBExcel($filter = [])
    {
        $between = "Money >= 10000 AND NameBank IS NOT NULL AND NumberBank IS NOT NULL AND FullName IS NOT NULL";
        $users = $this->GetWithPaginate(1, 100000, $filter, "Money DESC", $between);
        $data = [];
        $index = 1;

        foreach ($users as $user) {
            if ($user->Price <= 0)
                continue;
            $bank = $this->bankService->GetByCode($user->BankCode);
            if ($bank == null) {
                continue;
            }
            $fullName = strtoupper(Helper::remove_vietnamese_diacritics($user->FullName ?? ''));
            $cardNumber =  strval($user->NumberBank ?? '');
            //
            array_push($data, [
                'Txn' => Helper::NumberToString($index++, 4),
                'Price' => $user->Price ?? 0,
                'Name' => $fullName,
                'CardNumber' => $cardNumber,
                'Note' => 'Thanh toan tien khach hang ' . $user->Username ?? '',
                'BankName' => $bank->NameTCB . '-' . $bank->Code ?? '',
                // 'Province' => '',
                // 'Branch' => ''
            ]);
        }
        if (empty($data)) {
            return null;
        }
        $templatePath = $this->rootPath . '/Resources/Templates/DanhSachTaiKhoanTCB.xlsx';
        $outputPath = $this->rootPath . '/Resources/Exports/DanhSachTaiKhoanTCB_' . time() . '.xlsx';
        $file = ExcelHelper::exportToExcelNoProperty($data, $templatePath, $outputPath, 2);

        return $file;
    }

    /**
     *
     * @param mixed $top
     */
    public function GetTopUser($top = 10)
    {
        $startFirstDay = date('Y-m-01');
        $startLastDay = date('Y-m-t');
        $buildSql = "
			SELECT u.Id,u.Username, SUM(p.Price) as TotalPrice
			FROM users u
			LEFT JOIN payment_transactions p ON u.Id = p.UserId AND p.Status = 1 AND p.Type = 0
			WHERE p.CreatedAt BETWEEN '$startFirstDay' AND '$startLastDay'
			GROUP BY u.Username
			ORDER BY TotalPrice DESC
			LIMIT $top
			
		";
        $data = $this->context->fetch($buildSql);
        $users = [];
        foreach ($data as $item) {
            $user = new stdClass();
            $user->Id = $item['Id'];
            $user->Username = Helper::hiddenChar($item['Username']);
            $user->Money = Helper::formatCurrencyVND($item['TotalPrice']);
            array_push($users, $user);
        }
        return $users;
    }
}
