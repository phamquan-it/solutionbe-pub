<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['name' => 'Bank of America', 'icon' => 'boa.png', 'code' => 1001, 'shortname' => 'BOA'],
            ['name' => 'JPMorgan Chase', 'icon' => 'jpm.png', 'code' => 1002, 'shortname' => 'JPMC'],
            ['name' => 'Wells Fargo', 'icon' => 'wf.png', 'code' => 1003, 'shortname' => 'WF'],
            ['name' => 'Citibank', 'icon' => 'citi.png', 'code' => 1004, 'shortname' => 'CITI'],
            ['name' => 'Goldman Sachs', 'icon' => 'gs.png', 'code' => 1005, 'shortname' => 'GS'],
            ['name' => 'Morgan Stanley', 'icon' => 'ms.png', 'code' => 1006, 'shortname' => 'MS'],
            ['name' => 'PNC Bank', 'icon' => 'pnc.png', 'code' => 1007, 'shortname' => 'PNC'],
            ['name' => 'TD Bank', 'icon' => 'td.png', 'code' => 1008, 'shortname' => 'TD'],
            ['name' => 'Capital One', 'icon' => 'capone.png', 'code' => 1009, 'shortname' => 'COF'],
            ['name' => 'US Bank', 'icon' => 'usb.png', 'code' => 1010, 'shortname' => 'USB'],
            ['name' => 'HSBC', 'icon' => 'hsbc.png', 'code' => 1011, 'shortname' => 'HSBC'],
            ['name' => 'Barclays', 'icon' => 'barclays.png', 'code' => 1012, 'shortname' => 'BARC'],
            ['name' => 'Deutsche Bank', 'icon' => 'db.png', 'code' => 1013, 'shortname' => 'DB'],
            ['name' => 'UBS', 'icon' => 'ubs.png', 'code' => 1014, 'shortname' => 'UBS'],
            ['name' => 'Santander', 'icon' => 'santander.png', 'code' => 1015, 'shortname' => 'SAN'],
            ['name' => 'Lloyds Bank', 'icon' => 'lloyds.png', 'code' => 1016, 'shortname' => 'LLOYDS'],
            ['name' => 'BNP Paribas', 'icon' => 'bnp.png', 'code' => 1017, 'shortname' => 'BNP'],
            ['name' => 'Societe Generale', 'icon' => 'sg.png', 'code' => 1018, 'shortname' => 'SG'],
            ['name' => 'Standard Chartered', 'icon' => 'sc.png', 'code' => 1019, 'shortname' => 'SC'],
            ['name' => 'ANZ Bank', 'icon' => 'anz.png', 'code' => 1020, 'shortname' => 'ANZ'],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}
