<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\web_loan_application;
class OutStandingCollectionsChart
{
    protected $chart_collections;

    public function __construct(LarapexChart $chart_collections)
    {
        $this->chart_collections = $chart_collections;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {

        // Civil Servants

        // January
        $januaryCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 1)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // February
        $februaryCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 2)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // March
        $marchCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 3)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // April
        $aprilCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 4)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // May
        $mayCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 5)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // June
        $juneCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 6)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // July
        $julyCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 7)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // August
        $augustCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 8)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // September
        $septemberCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 9)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // October
        $octoberCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 10)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // November
        $novemberCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 11)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // December
        $decemberCivilServants = web_loan_application::where('approved',"=",1)->where('loan_type',"=",1)->whereMonth('created_at', '=', 12)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
     
        


// Auto Loans

        // January
        $januaryAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 1)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // February
        $februaryAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 2)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // March
        $marchAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 3)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // April
        $aprilAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 4)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // May
        $mayAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 5)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // June
        $juneAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 6)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // July
        $julyAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 7)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // August
        $augustAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 8)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // September
        $septemberAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 9)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // October
        $octoberAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 10)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // November
        $novemberAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 11)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // December
        $decemberAuto = web_loan_application::where('approved',"=",1)->where('loan_type',"=",2)->whereMonth('created_at', '=', 12)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
       
        




        // Private Loans

        // January
        $januaryPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 1)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // February
        $februaryPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 2)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // March
        $marchPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 3)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // April
        $aprilPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 4)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // May
        $mayPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 5)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // June
        $junePrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 6)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // July
        $julyPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 7)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // August
        $augustPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 8)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // September
        $septemberPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 9)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // October
        $octoberPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 10)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // November
        $novemberPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 11)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        
        // December
        $decemberPrivate = web_loan_application::where('approved',"=",1)->where('loan_type',"=",3)->whereMonth('created_at', '=', 12)->whereYear('created_at', '=', date('Y'))->sum('loan_amount');
        




        return $this->chart_collections->lineChart()
            ->setTitle('OutStanding Balance '.date('F Y'))
            ->setSubtitle('Civil Servants, Private Sector & Auto Loans')
            ->addData('Civil Servants', [$januaryCivilServants, $februaryCivilServants, $marchCivilServants, $aprilCivilServants, $mayCivilServants, $juneCivilServants,$julyCivilServants, $augustCivilServants, $septemberCivilServants, $octoberCivilServants, $novemberCivilServants, $decemberCivilServants])
            ->addData('Private Sector', [$januaryAuto, $februaryAuto, $marchAuto, $aprilAuto, $mayAuto, $juneAuto,$julyAuto, $augustAuto, $septemberAuto, $octoberAuto, $novemberAuto, $decemberAuto])
            ->addData('Auto Based', [$januaryPrivate, $februaryPrivate, $marchPrivate, $aprilPrivate, $mayPrivate, $junePrivate,$julyPrivate, $augustPrivate, $septemberPrivate, $octoberPrivate, $novemberPrivate, $decemberPrivate])
            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June','July', 'August', 'Sept', 'Oct', 'Nov', 'December']);
    }
}
