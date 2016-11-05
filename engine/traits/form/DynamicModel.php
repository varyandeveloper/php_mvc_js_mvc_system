<?php
/**
 * Created by PhpStorm.
 * User: Var Yan
 * Date: 6/12/2016
 * Time: 11:12 AM
 */

namespace engine\traits\form;

use engine\Engine;
use engine\objects\Factory;
use engine\objects\Model;

/**
 * Class DynamicModel
 * @package engine\traits\form
 */
trait DynamicModel
{
    /**
     * @var \stdClass $__item
     */
    private $__item;
    /**
     * @var Model $__model
     */
    private $__model;

    /**
     * @param string $modelName
     * @param \stdClass|null $item
     * @return string
     */
    public function model(string $modelName,$item = null)
    {
        $model = Factory::create($modelName);
        $this->__model = $model;
        $this->__item = $item;
        return $this->__collectModelForm();
    }

    /**
     * @return string
     */
    private function __collectModelForm()
    {
        $columns = $this->__model->fields(false);
        for($i = 0; $i < sizeof($columns); $i++)
        {
            if(!in_array($columns[$i]['Field'],$this->__model->getHidden()))
            {
                switch (TRUE):
                    case Engine::startsWith($columns[$i]['Type'],"text"):
                        $this->_textField($columns[$i]);
                        break;
                    case Engine::startsWith($columns[$i]['Type'],"enum"):
                        $this->_enumField($columns[$i]);
                        break;
                    case Engine::startsWith($columns[$i]['Type'],"varchar"):
                    case Engine::startsWith($columns[$i]['Type'],"char"):
                        $this->_varcharField($columns[$i]);
                        break;
                    case Engine::startsWith($columns[$i]['Type'],"int"):
                    case Engine::startsWith($columns[$i]['Type'],"tinyint"):
                        $this->_integerField($columns[$i]);
                        break;
                endswitch;
            }
        }
        $this->openTag("hr")->closeTag()
            ->button("Submit","submit","class='btn btn-primary pull-right'");
        return $this;
    }

    /**
     * @param array $column
     */
    protected function _textField($column)
    {
        $this->openTag("div","class='form-group'")
            ->label(ucfirst($column['Field']),"{$column['Field']}","class='control-label'")
            ->textarea($column['Field'],@$this->__item->{$column['Field']},"class='form-control'")
            ->closeTag();
    }

    /**
     * @param array $column
     */
    protected function _enumField($column)
    {
        $this->openTag("div","class='form-group'")
            ->label(ucfirst($column['Field']),"{$column['Field']}","class='control-label'")
            ->select("",$this->__model->enumList($column['Field']),@$this->__item->{$column['Field']},"class='form-control'","Select ".ucfirst($column['Field']))
            ->closeTag();
    }

    /**
     * @param array $column
     */
    protected function _varcharField($column)
    {
        $type = "text";

        if($column['Field'] == "password")
            $type = "password";
        elseif ($column['Field'] == "email")
            $type = "email";
        elseif ($column['Field'] == "image" || $column['Field'] == "file"){
            $this->optional("enctype='multipart/form-data'");
            $type = "file";
        }

        $this->openTag("div","class='form-group'")
            ->label(!empty(langLine($column['Field'])) ? langLine($column['Field']) : ucfirst($column['Field']),"{$column['Field']}","class='control-label'")
            ->input($column['Field'],"{$type}","id='{$column['Field']}' class='form-control' value='".@$this->__item->{$column['Field']}."'")
            ->closeTag();
    }

    /**
     * @param array $column
     */
    protected function _integerField($column)
    {
        $length = (int)$int = filter_var($column['Type'], FILTER_SANITIZE_NUMBER_INT);
        if($length == 1)
        {
            $checked = "";
            if(@$this->__item->{$column['Field']})
                $checked = "checked";
            $this->openTag("div","class='checkbox'")
                ->openTag("label","",$column['Field'])
                ->input($column['Field'],"checkbox","id='{$column['Field']}' value='1' {$checked}")
                ->closeTag()
                ->closeTag();
        }
        else{
            if($column['Key'] != "PRI")
            {
                if($column['Key'] == "MUL")
                {
                    $parentModel = @$this->__model->getConnections()[$column['Field']];
                    if(!is_null($parentModel) && is_string($parentModel['model']))
                    {
                        $model = Factory::create($parentModel['model']);
                        $this->openTag("div","class='form-group'")
                            ->label(ucfirst($column['Field']),"{$column['Field']}","class='control-label'")
                            ->select("",$model->all(),@$this->__item->{$column['Field']},"class='form-control'","Select ".ucfirst($column['Field']),$parentModel["fields"])
                            ->closeTag();
                    }
                }
                else
                {
                    $this->openTag("div","class='form-group'")
                        ->label(ucfirst($column['Field']),"{$column['Field']}","class='control-label'")
                        ->input($column['Field'],"number","id='{$column['Field']}' class='form-control'")
                        ->closeTag();
                }
            }
        }
    }
}