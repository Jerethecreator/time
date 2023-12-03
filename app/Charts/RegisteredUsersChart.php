<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\reg_employee_mst;
class RegisteredUsersChart
{
    protected $users;

    public function __construct(LarapexChart $users)
    {
        $this->users = $users;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {

        // Civil Servants

        // January
        $januaryUsers = reg_employee_mst::whereMonth('created_dt', '=', 1)->whereYear('created_dt', '=', date('Y'))->count();
        
        // February
        $februaryUsers = reg_employee_mst::whereMonth('created_dt', '=', 2)->whereYear('created_dt', '=', date('Y'))->count();
        
        // March
        $marchUsers = reg_employee_mst::whereMonth('created_dt', '=', 3)->whereYear('created_dt', '=', date('Y'))->count();
        
        // April
        $aprilUsers = reg_employee_mst::whereMonth('created_dt', '=', 4)->whereYear('created_dt', '=', date('Y'))->count();
        
        // May
        $mayUsers = reg_employee_mst::whereMonth('created_dt', '=', 5)->whereYear('created_dt', '=', date('Y'))->count();
        
        // June
        $juneUsers = reg_employee_mst::whereMonth('created_dt', '=', 6)->whereYear('created_dt', '=', date('Y'))->count();
        
        // July
        $julyUsers = reg_employee_mst::whereMonth('created_dt', '=', 7)->whereYear('created_dt', '=', date('Y'))->count();
        
        // August
        $augustUsers = reg_employee_mst::whereMonth('created_dt', '=', 8)->whereYear('created_dt', '=', date('Y'))->count();
        
        // September
        $septemberUsers = reg_employee_mst::whereMonth('created_dt', '=', 9)->whereYear('created_dt', '=', date('Y'))->count();
        
        // October
        $octoberUsers = reg_employee_mst::whereMonth('created_dt', '=', 10)->whereYear('created_dt', '=', date('Y'))->count();
        
        // November
        $novemberUsers = reg_employee_mst::whereMonth('created_dt', '=', 11)->whereYear('created_dt', '=', date('Y'))->count();
        
        // December
        $decemberUsers = reg_employee_mst::whereMonth('created_dt', '=', 12)->whereYear('created_dt', '=', date('Y'))->count();
     
        

        return $this->users->lineChart()
            ->setTitle('Registered Users ' .date('Y'))
            ->setSubtitle('Monthly Registered Users')
            ->addData('registered users', [$januaryUsers, $februaryUsers, $marchUsers, $aprilUsers, $mayUsers, $juneUsers,$julyUsers, $augustUsers, $septemberUsers, $octoberUsers, $novemberUsers, $decemberUsers])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June','July', 'August', 'Sept', 'Oct', 'Nov', 'December']);
    }
}
