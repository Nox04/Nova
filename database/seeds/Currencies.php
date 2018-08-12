<?php

namespace Database\Seeds;

use App\Models\Model;
use App\Models\Setting\Currency;
use Illuminate\Database\Seeder;

class Currencies extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        $rows = [

            [
                'company_id' => $company_id,
                'name' => trans('demo.currencies_cop'),
                'code' => 'COP',
                'rate' => '1.00',
                
                'precision' => config('money.COP.precision'),
                'symbol' => config('money.COP.symbol'),
                'symbol_first' => config('money.COP.symbol_first'),
                'decimal_mark' => config('money.COP.decimal_mark'),
                'thousands_separator' => config('money.COP.thousands_separator'),
            ]
        ];

        foreach ($rows as $row) {
            Currency::create($row);
        }
    }
}
