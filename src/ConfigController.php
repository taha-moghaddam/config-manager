<?php

namespace Bikaraan\BCore\Config;

use Bikaraan\BCore\Controllers\HasResourceActions;
use Bikaraan\BCore\Facades\Admin;
use Bikaraan\BCore\Form;
use Bikaraan\BCore\Grid;
use Bikaraan\BCore\Layout\Content;
use Bikaraan\BCore\Show;

class ConfigController
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(__('admin.config'))
            ->description(__('admin.list'))
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param int     $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(__('admin.config'))
            ->description(__('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(__('admin.config'))
            ->description(__('admin.create'))
            ->body($this->form());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header(__('admin.config'))
            ->description(__('admin.detail'))
            ->body(Admin::show(ConfigModel::findOrFail($id), function (Show $show) {
                $show->id(__('admin.id'));
                $show->name(__('admin.name'));
                $show->value(__('admin.value'));
                $show->description(__('admin.description'));
                $show->created_at(__('admin.created_at'));
                $show->updated_at(__('admin.updated_at'));
            }));
    }

    public function grid()
    {
        $grid = new Grid(new ConfigModel());

        $grid->id(__('admin.id'))->sortable();
        $grid->name(__('admin.name'))->display(function ($name) {
            return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
        })->sortable();
        $grid->value(__('admin.value'))->sortable();
        $grid->description(__('admin.description'));

        $grid->updated_at(__('admin.updated_at'))->sortable();

        $grid->filter(function ($filter) {
            $filter->column(1 / 2, function ($filter) {
                $filter->like('name', __('admin.name'));
            });

            $filter->column(1 / 2, function ($filter) {
                $filter->like('value', __('admin.value'));
                $filter->like('description', __('admin.description'));
            });
        });

        return $grid;
    }

    public function form()
    {
        $form = new Form(new ConfigModel());

        $form->display('id', __('admin.id'));
        $form->text('name', __('admin.name'))->rules('required');
        if (config('bcore.extensions.config.valueEmptyStringAllowed', false)) {
            $form->textarea('value', __('admin.value'));
        } else {
            $form->textarea('value', __('admin.value'))->rules('required');
        }
        $form->textarea('description', __('admin.description'));

        $form->display('created_at', __('admin.created_at'));
        $form->display('updated_at', __('admin.updated_at'));

        return $form;
    }
}
