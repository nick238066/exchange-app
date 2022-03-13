<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Exception;
use App\Models\WalletAddress;
use App\Services\WalletAddressService;

class GetExchangeWalletBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getExchangeWalletBalance {id : Exchange Wallet Id} {--contract_token= : 合約地址}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '取得錢包地址餘額或合約餘額';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $address = WalletAddress::find($this->argument('id'));

            $contract_token = $this->option('contract_token');

            DB::beginTransaction();

            $result = App::call(WalletAddressService::class . '@getExchangeWalletBalance', ['address' => $address, 'contract_token' => $this->option('contract_token')]);
            if ($result['status'] == 'fail') {
                throw new Exception("錢包地址餘額查詢失敗");
            }

            DB::commit();

            $this->info('Success');
            return 1;

        } catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }

        return 0;
    }
}
