<?php

namespace App\Admin\Controllers;

use App\Models\School;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SchoolController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '学校';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new School);

        $grid->column('name', '学校名称');
        $grid->column('initial', '首字母');
        $grid->column('longitude', '经度');
        $grid->column('latitude', '纬度');
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

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
        $show = new Show(School::findOrFail($id));

        $show->field('name', '学校名称');
        $show->field('initial', '首字母');
        $show->field('longitude', '经度');
        $show->field('latitude', '维度');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new School);

        $form->text('name', '学校名称');
        $form->text('initial', '首字母')->default('A');
        $form->text('longitude', '经度');
        $form->text('latitude', '维度');

        return $form;
    }
}
