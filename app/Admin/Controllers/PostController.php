<?php

namespace App\Admin\Controllers;

use App\Models\Post;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '推文';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post);

        $grid->column('user.nickname', '作者');
        $grid->column('school.name', '所属学校');
        $grid->column('content','发布内容')->display(function ($content){
            return Str::limit($content, 40);
        });
        $grid->column('resource_type', '资源类型')->display(function ($type) {
            return Post::resourceTypeMaps()[$type] ?? '';
        });
        $grid->column('repost_count', '转发次数');
        $grid->column('like_count', '喜欢次数');
        $grid->column('dislike_count', '不喜欢次数');
        $grid->column('comment_count', '评论次数');
        $grid->column('verify_status', '审核状态')->display(function ($status) {
            return Post::verifyStatusMaps()[$status] ?? '';
        });
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
        $model = Post::findOrFail($id);
        $show = new Show($model);

        $show->user('作者')->as(function ($user){
            return $user->nickname;
        });
        $show->field('school_id', '所属学校');
        $show->field('content','发布内容');
        $show->field('resource_type', '资源类型');
        $show->field('repost_count', '转发次数');
        $show->field('like_count', '喜欢次数');
        $show->field('dislike_count', '不喜欢次数');
        $show->field('comment_count', '评论次数');
        $show->field('verify_status', '审核状态');
        if ($model->resource_type == Post::RESOURCE_TYPE_VIDEO) {
            $show->field('resource_urls', '视频')->video(['videoWidth' => 720, 'videoHeight' => 480]);
        } else {
            $show->field('resource_urls', '图片')->carousel();
        }
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
        $form = new Form(new Post);

        $form->text('user_id', '作者');
        $form->textarea('content','发布内容');
        $form->switch('resource_type', '资源类型')->disable();
//        $form->textarea('resource_urls', __('Resource urls'));
        $form->number('repost_count', '转发次数')->disable();
        $form->number('like_count', '喜欢次数')->disable();
        $form->number('dislike_count', '不喜欢次数')->disable();
        $form->number('comment_count', '评论次数')->disable();
        $form->radio('verify_status', '审核状态')->options(Post::verifyStatusMaps());

        return $form;
    }
}
