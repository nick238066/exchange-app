<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\AdminUser;
use App\Services\AdminUserService;
use Exception;

class StoreUserExchangeAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:storeUserExchangeAddress {user_id : 使用者ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '建立使用者交易所錢包地址';

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
            $userId = $this->argument('user_id');
            $user = AdminUser::find($userId);
            if (!$user) {
                throw new Exception("使用者不存在");
            }

            DB::beginTransaction();

            $add_count = App::call(AdminUserService::class . '@createUserExchangeAddress', ['user' => $user]);
            if ($add_count <= 0) {
                throw new Exception("未新增錢包地址");
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }

        return 0;
    }
}
