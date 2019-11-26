<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '广告图管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner);

        $grid->column('title', '标题');
        $grid->column('introduction', '简介');
        $grid->column('code', 'banner码');
        $grid->column('picture_url', '图片地址');
        $grid->column('url', '图片对应链接');
        $grid->column('disabled', '是否禁用');
        $grid->column('link_type', '链接类型');
        $grid->column('sort', '排序值')->editable();
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
        $show = new Show(Banner::findOrFail($id));

        $show->field('title', '标题');
        $show->field('introduction', '简介');
        $show->field('code', 'banner码');
        $show->field('picture_url', '图片地址');
        $show->field('url', '图片对应链接');
        $show->field('disabled', '是否禁用');
        $show->field('link_type', '链接类型');
        $show->field('sort', '排序值');
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
        $form = new Form(new Banner);

        $form->text('title', '标题');
        $form->text('introduction', '简介');
        $form->text('code', 'banner码');
        $form->url('picture_url', '图片地址');
        $form->url('url', '图片对应链接');
        $form->switch('disabled', '是否禁用');
        $form->radio('link_type', '链接类型')->options(Banner::linkTypeMaps());

        return $form;
    }
}
