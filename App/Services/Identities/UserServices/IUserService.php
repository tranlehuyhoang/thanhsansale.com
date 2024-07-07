<?php 
namespace App\Services\Identities\UserServices;

use App\Services\Interfaces\IBaseInterface;

interface IUserService extends IBaseInterface
{
    public function GetByUsername($username);
    public function GetByEmail($email);

    public function Login($username, $password);
    public function Register($user);

    public function UpdatePassword($userId, $password);
    public function GetWithPaginate($pageIndex, $pageSize, $filter = [],$orderBy = NULL, $between = NULL);

    public function AddMoney($items); // Add money to users account
    public function SubtractMoney($items);     // Subtract money from users account
    public function ResetAllMoney($userId = NULL); // Reset all money to 0
    public function AddMoneyByUser($userId,$money=0); // Reset all money to 0
    public function ExportExcel($filter = []);
    public function ExportVPBankExcel($filter = []);
    public function ExportBIDVExcel($filter = []);
    public function ExportTCBExcel($filter = []);

    public function GetTopUser($top = 10);
    
}