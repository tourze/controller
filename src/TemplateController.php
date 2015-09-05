<?php

namespace tourze\Controller;

use tourze\Base\Base;
use tourze\View\View;
use tourze\View\ViewInterface;

/**
 * 最基础的模板控制器，实现页面布局分离功能
 *
 * @property bool        autoRender
 * @property string|View template
 * @package tourze\Controller
 */
abstract class TemplateController extends WebController
{

    /**
     * @var  string|View  模板名，或者模板对象
     */
    protected $_template = 'template';

    /**
     * @return string|View
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * @param string|View $template
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    /**
     * @var bool 是否自动加载模板
     **/
    protected $_autoRender = true;

    /**
     * @return boolean
     */
    public function isAutoRender()
    {
        return $this->_autoRender;
    }

    /**
     * @param boolean $autoRender
     */
    public function setAutoRender($autoRender)
    {
        $this->_autoRender = $autoRender;
    }

    /**
     * 初始化，并加载模板对象
     */
    public function before()
    {
        parent::before();

        if ($this->autoRender)
        {
            Base::getLog()->debug(__METHOD__ . ' enable auto render');
            if ( ! $this->template instanceof ViewInterface)
            {
                Base::getLog()->debug(__METHOD__ . ' create template view instance', [
                    'template' => $this->template,
                ]);
                $this->template = View::factory($this->template);
            }
        }
        else
        {
            Base::getLog()->debug(__METHOD__ . ' disable auto render');
        }
    }

    /**
     * 完成模板渲染，并输出
     */
    public function after()
    {
        if ($this->autoRender)
        {
            Base::getLog()->debug(__METHOD__ . ' render template view');
            $this->response->body = $this->template->render();
        }
        parent::after();
    }
}
