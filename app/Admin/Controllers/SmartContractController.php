<?php

namespace App\Admin\Controllers;

use App\Models\SmartContract;
use App\Models\ExchangeAddressType;
use App\Models\EnvSetting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SmartContractController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '智能合約管理';

    protected $exchangeAddressTypes;
    protected $envSettings;

    public function __construct()
    {
        $this->exchangeAddressTypes = ExchangeAddressType::All()->pluck('name', 'id');
        $this->envSettings = EnvSetting::All()->pluck('name', 'id');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SmartContract());

        $grid->column('id', __('Id'));
        $grid->column('env_setting.name', __('Env setting id'));
        $grid->column('address_type.name', __('Address type id'));
        $grid->column('name', __('Name'));
        $grid->column('short_name', __('Short name'));
        $grid->column('token', __('Token'));
        $grid->column('decimals', __('Decimals'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(SmartContract::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('env_setting_id', __('Env setting id'));
        $show->field('address_type_id', __('Address type id'));
        $show->field('name', __('Name'));
        $show->field('short_name', __('Short name'));
        $show->field('token', __('Token'));
        $show->field('decimals', __('Decimals'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SmartContract());

        $form->select('env_setting_id', __('Env setting id'))->options($this->envSettings);
        $form->select('address_type_id', __('Address type id'))->options($this->exchangeAddressTypes);
        $form->text('name', __('Name'));
        $form->text('short_name', __('Short name'));
        $form->text('token', __('Token'));
        $form->text('decimals', __('Decimals'));

        return $form;
    }
}
